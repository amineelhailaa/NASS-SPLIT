<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupFormRequest;
use App\Models\Group;
use Illuminate\Http\Request;

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
        $path = null;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
        }
        Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'avatar' =>$path,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
