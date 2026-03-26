<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExpenseFormRequest;
use App\Models\Expense;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    use ApiResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        Gate::authorize('member',$group);
        return $this->successResponse($group->expenses()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseFormRequest $request,Group $group)
    {
        Gate::authorize('member',$group);
        $user = $request->user();
        $group->expenses()->create([
            'payer_id' => $request->payer_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount
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
