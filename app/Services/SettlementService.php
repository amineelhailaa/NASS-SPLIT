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
        while (!empty($creditors)){

        }
    }

}
