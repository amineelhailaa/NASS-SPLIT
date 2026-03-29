<?php

namespace App\Services;

use App\Models\Group;

class SettlementService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function forGroup(Group $group)
    {
        $memberships = $group->members(); //memberships :)
        $netBalances = [];
        foreach ($memberships as $member) {
            $credits = round($member->splitsAsCreditor()->where('status', 'pending')->sum('amount') * 100);
            $debits = round($member->splitsAsDebitor()->where('status', 'pending')->sum('amount') * 100);
            $netBalances[$member->id] = $credits - $debits;
        }

        return $this->buildTransactions($netBalances);
    }

    public function buildTransactions($netBalances)
    {
        $creditors = [];
        $debitors = [];

        foreach ($netBalances as $memberId => $balance) {
            if ($balance < 0) {
                $debitors[] = [
                    'membership_id' => $memberId,
                    'amount' => abs($balance)
                ];
            } elseif ($balance > 0) {
                $creditors[] = [
                    'membership_id' => $memberId,
                    'amount' => $balance
                ];
            }
        }


        $transactions = [];
        while (!empty($creditors)) {
            $creditors = collect($creditors)
                ->sortByDesc('amount')
                ->values(); //reset keys after sorting
            $debitors = collect($debitors)
                ->sortByDesc('amount')
                ->values();

            $creditor = $creditors->shift();
            $debitor = $debitors->shift();

            //reset to array =>
            $creditors = $creditors->all();
            $debitors = $debitors->all();

            $amountOfTransaction = min($creditor['amount'], $debitor['amount']);
            $transactions[] = [
                'debtor_id' => $debitor['membership_id'],
                'creditor_id' => $creditor['membership_id'],
                'amount' => $amountOfTransaction / 100
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
