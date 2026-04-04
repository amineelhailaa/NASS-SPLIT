<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GroupFormRequest;
use App\Models\Group;
use App\Services\GroupService;
use App\Services\SettlementService;
use Illuminate\Http\Request;
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
    ){

    }
    public function index(Request $request)
    {
        $is_admin = $request->user()->admin()->exists();
        $query = $is_admin ? Group::query()
            : $request->user()->groups();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%$searchTerm%");
        }
        //sort
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
       $group =  $this->groupService->createGroup($user,$request);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $group->avatar()->create([
                'path'=>$path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->isImage() ? 'image':'file'
            ]);
        }

        return $this->createdResponse($group);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = Group::findOrFail($id);
        Gate::authorize('member',$group);
        return $this->successResponse($group);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupFormRequest $request, string $id)
    {
        $group =   Group::findOrFail($id);
        Gate::authorize('owner',[$group]);
        $group->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $group->avatar()->update([
                'path'=>$path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->isImage() ? 'image':'file'
            ]);
        }

        return $this->successResponse($group,'group updated successuflly !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::findOrFail($id);
        Gate::authorize('owner',[$group]);
        $group->delete();
       return $this->successResponse([],'group deleted');
    }


    public function members(Group $group){
        Gate::authorize('member',$group);
      return $this->successResponse($group->users()->with('avatar')->get());
    }


    public function statistics(Group $group){

    }

    public function myBalance(Request $request,Group $group){
        Gate::authorize('member',$group);
        $user = $request->user();
        $membership = $user->memberships()->where('group_id',$group->id)->firstOrFail();
       return $this->successResponse(
           $membership->splitsAsCreditor()
               ->where('splits.status','unpaid')
               ->sum('amount')
           - $membership->splitsAsDebtor()
               ->where('splits.status','unpaid')
               ->sum('amount'));
    }


    public function owes(Request $request, Group $group){
        Gate::authorize('member',$group);
       $owes =  $this->settlementService->forGroup($group);
       return $this->successResponse($owes);
    }

}
