<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Membership;

class SettlementService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function memberBalance(Membership $membership): float
    {
        $totalCredits = $membership->splitsAsCreditor()->sum('amount')
            + $membership->paymentsAsDebtor()->sum('amount');

        $totalDebits = $membership->splitsAsDebtor()->sum('amount')
            + $membership->paymentsAsCreditor()->sum('amount');

        return $totalCredits - $totalDebits;
    }

    public function forGroup(Group $group): array
    {
        $netBalances = [];
        foreach ($group->members()->get() as $member) {
            $netBalances[$member->id] = round($this->memberBalance($member) * 100);
        }

        return $this->buildTransactions($netBalances);
    }

    public function buildTransactions($netBalances): array
    {
        $creditors = [];
        $debitors = [];

        foreach ($netBalances as $memberId => $balance) {
            if ($balance < 0) {
                $debitors[] = [
                    'membership_id' => $memberId,
                    'amount' => abs($balance),
                ];
            } elseif ($balance > 0) {
                $creditors[] = [
                    'membership_id' => $memberId,
                    'amount' => $balance,
                ];
            }
        }

        $transactions = [];
        while (! empty($creditors)) {
            $creditors = collect($creditors)
                ->sortByDesc('amount')
                ->values(); // reset keys after sorting
            $debitors = collect($debitors)
                ->sortByDesc('amount')
                ->values();

            $creditor = $creditors->shift();
            $debitor = $debitors->shift();

            // reset to array =>
            $creditors = $creditors->all();
            $debitors = $debitors->all();

            $amountOfTransaction = min($creditor['amount'], $debitor['amount']);
            $transactions[] = [
                'debtor_id' => $debitor['membership_id'],
                'creditor_id' => $creditor['membership_id'],
                'amount' => $amountOfTransaction / 100,
            ];

            $creditor['amount'] -= $amountOfTransaction;
            $debitor['amount'] -= $amountOfTransaction;

            if ($creditor['amount'] > 0) {
                $creditors[] = $creditor;
            }
            if ($debitor['amount'] > 0) {
                $debitors[] = $debitor;
            }
        }

        return $transactions;
    }
}
