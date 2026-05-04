<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        return $this->successResponse($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $user->update([
            'name' => $request->name,
        ]);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('', 'public');

            $user->avatar()->updateOrCreate([], [
                'path' => $path,
                'file_type' => 'image',
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return $this->successResponse($user, 'updated');
    }
    /**
     * Remove the specified resource from storage.
     */
}
