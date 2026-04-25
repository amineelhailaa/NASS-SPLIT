<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaymentRequest;
use App\Models\Group;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Group $group)
    {
        $user = $request->user();
        $membership = $group->members()->where('status', 'active')
            ->where('user_id', $user->id)
            ->firstOrFail();
        $payments = Payment::query()
            ->where(function ($q) use ($membership) {
                $q->where('creditor_id', $membership->id)
                    ->orWhere('debtor_id', $membership->id);
            })
            ->with('debtor.user', 'creditor.user')
            ->paginate();

        return $this->successResponse($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        $user = $request->user();
        // need validation here dont forget it .
        $user->memberships()->whereIn('id',
            [$request->creditor_id, $request->debtor_id])->firstOrFail();
        $payment = Payment::create([
            'creditor_id' => $request->creditor_id,
            'debtor_id' => $request->debtor_id,
            'amount' => $request->amount,
        ]);

        return $this->createdResponse($payment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::findOrFail($id);

        return $this->successResponse($payment);
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
