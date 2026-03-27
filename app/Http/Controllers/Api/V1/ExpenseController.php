<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExpenseFormRequest;
use App\Models\Expense;
use App\Models\Group;
use App\Models\Split;
use App\Services\StrategyManagerService;
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
    public function store(ExpenseFormRequest $request,Group $group,StrategyManagerService $service)
    {
        Gate::authorize('member',$group);
        $user = $request->user();
       $expense = $group->expenses()->create([
            'payer_id' => $request->payer_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'date'=> $request->date,
            'amount' => $request->amount
        ]);
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {

                $path = $file->store('', 'public');

                $expense->attachments()->create([
                    'path'=> $path,
                    'file_type'=> $file->isImage ? 'image' : 'file',
                    'file_name'=> $file->getClientOriginalName()
                ]);
            }
        }
        $splits = $service->dataToInsert($request->split_strategy,$request->amount,$request->participants);
        //now im looking for a function to insert collection without looping
        $now = now();
        $splits = array_map(fn($item)=>[
            ...$item,'expense_id'=> $expense->id,
            'created_at'=> $now,
            'updated_at'=>$now
        ],$splits);
        Split::insert($splits);
        return $this->createdResponse($expense,'created successfully');
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
