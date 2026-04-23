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
        $groups = $query
            ->with('avatar', 'users')
            ->withCount(['members' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('groups.'.$sortBy, $sortType)
            ->paginate(9);

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
    public function show(Group $group, Request $request)
    {
        Gate::authorize('member', $group);

        $group->load('conversation', 'users');
        $group->pivot = $group->members()
            ->where('user_id', $request->user()->id)
            ->first();

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
            $group->avatar()->updateOrCreate([], [
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

        return $this->noContentResponse();
    }

    public function members(Group $group)
    {
        Gate::authorize('member', $group);

        // return ids to apply membership controller methods
        return $this->successResponse(
            $group->members()
                ->where('status', 'active')
                ->with('user.avatar')
                ->paginate(10)
        );
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
            ->with('category')
            ->get();
        $today = today();
        $start = $today->copy()->subDays(29);

        $Daily = Expense::where('group_id', $group->id)
            ->whereBetween('date', [$start->toDateString(), $today->toDateString()])
            ->selectRaw('date as day, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'day');
        $dailySpending = collect();
        for ($i = 29; $i >= 0; $i--) {
            $day = $today->copy()->subDays($i)->toDateString();
            $dailySpending->push([
                'day' => $day,
                'total' => (float) ($Daily->get($day) ?? 0),
            ]);
        }

        $membership = $user->memberships()->where('group_id', $group->id)->firstOrFail();
        $paidByMe = $membership->expensesPaid()->sum('expenses.amount');
        $myShare = $membership->splitsAsDebtor()->sum('splits.amount')
            - $membership->splitsAsCreditor()->sum('splits.amount');

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
            'creditor_id' => $owe['creditor_id'],
            'debtor_id' => $owe['debtor_id'],
            'creditor' => $members[$owe['creditor_id']]->user,
            'debtor' => $members[$owe['debtor_id']]->user,
            'amount' => $owe['amount'],
        ], $owes);

        return $this->successResponse($owes);
    }

    public function changeSettings(Group $group, Request $request)
    {
        $validated = $request->validate([
            'settle' => ['required', 'boolean'],
        ]);
        $group->update(['settle' => $validated['settle']]);

        return $this->successResponse($group);
    }
}
