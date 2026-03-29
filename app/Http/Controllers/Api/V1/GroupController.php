<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupFormRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
    use ApiResponses;
    /**
     * Display a listing of the resource.
     */
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
       $group =  $user->groups()->create([ // should verify if i can createw grp with that relation !
            'name' => $request->name,
            'description' => $request->description,
        ]);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $group->avatar?->create([
                'path'=>$path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->isImage() ? 'image':'file'
            ]);
        }

        $user->groups()->updateExistingPivot($group->id,[
            'role' => 'owner'
        ]);
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
            $group->avatar?->updateOrCreate([
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


}
