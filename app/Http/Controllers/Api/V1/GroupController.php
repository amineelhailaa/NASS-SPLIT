<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GroupFormRequest;
use App\Models\Expense;
use App\Models\Group;
use App\Services\GroupService;
use App\Services\SettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function __construct(
        private GroupService $groupService,
        private SettlementService $settlementService
    ) {}

    public function index(Request $request)
    {
        $is_admin = $request->user()->admin()->exists();
        $query = $is_admin ? Group::query()
            : $request->user()->groups();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%$searchTerm%");
        }
        // sort
        $sortBy = in_array($request->input('sort_by'), ['created_at', 'name']) ? $request->input('sort_by') : 'created_at';
        $sortType = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $groups = $query->orderBy('groups.'.$sortBy, $sortType)->paginate(9);

        return $this->successResponse($groups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupFormRequest $request)
    {

        $user = $request->user();
        $group = $this->groupService->createGroup($user, $request);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $group->avatar()->create([
                'path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->isImage() ? 'image' : 'file',
            ]);
        }

        return $this->createdResponse($group);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        Gate::authorize('member', $group);

        return $this->successResponse($group);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupFormRequest $request, Group $group)
    {
        Gate::authorize('owner', [$group]);
        $group->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $group->avatar()->update([
                'path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->isImage() ? 'image' : 'file',
            ]);
        }

        return $this->successResponse($group, 'group updated successuflly !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        Gate::authorize('owner', [$group]);
        $group->delete();

        return $this->successResponse([], 'group deleted');
    }

    public function members(Group $group)
    {
        Gate::authorize('member', $group);

        // return ids to apply membership controller methods
        return $this->successResponse($group->members()->with('user.avatar')->get());
    }

    public function invitationCode(Group $group)
    {
        Gate::authorize('owner', $group);

        return $this->successResponse(['invitation_code' => $group->invitation_code]);
    }

    public function statistics(Group $group, Request $request)
    {
        $user = $request->user();
        Gate::authorize('member', $group);
        $totalExpense = $group->expenses()->count();
        $totalSpend = $group->expenses()->sum('amount');
        $totalMembers = $group->users()->count();
        $spendingByCategory = Expense::where('group_id', $group->id)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category:id,name')
            ->get();
        $dailySpending = Expense::where('group_id', $group->id)
            ->select(DB::raw('DATE(date) as day'),
                DB::raw('SUM(amount) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $membership = $user->memberships()->where('group_id', $group->id)->firstOrFail();
        $paidByMe = $membership->expensesPaid()->sum('amount');
        $myShare = $membership->splitsAsDebtor()->sum('amount')
            - $membership->splitsAsCreditor()->sum('amount');

        return $this->successResponse([
            'total_expenses' => $totalExpense,
            'total_spent' => $totalSpend,
            'total_members' => $totalMembers,
            'paid_by_me' => $paidByMe,
            'my_share' => $myShare,
            'spending_by_category' => $spendingByCategory,
            'daily_spending' => $dailySpending,
        ]);
    }

    public function myBalance(Request $request, Group $group)
    {
        Gate::authorize('member', $group);
        $user = $request->user();
        $membership = $user->memberships()->where('group_id', $group->id)->firstOrFail();

        $balance = $this->settlementService->memberBalance($membership);

        return $this->successResponse($balance);
    }

    public function owes(Request $request, Group $group)
    {
        $user = $request->user();
        Gate::authorize('member', $group);
        $owes = $this->settlementService->forGroup($group);
        $membership = $user->memberships()->where('group_id', $group->id)->firstOrFail();

        $owes = array_values(array_filter($owes, function ($owe) use ($membership) {
            return in_array($membership->id, [$owe['creditor_id'], $owe['debtor_id']]);
        }));
        $members = $group->members()->with('user.avatar')->get()->keyBy('id'); // make an assoc with ids inside
        $owes = array_map(fn ($owe) => [
            'creditor' => $members[$owe['creditor_id']]->user,
            'debtor' => $members[$owe['debtor_id']]->user,
            'amount' => $owe['amount'],
        ], $owes);

        return $this->successResponse($owes);
    }
}
