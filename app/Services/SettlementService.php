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
        return $this->memberBalanceInCents($membership) / 100;
    }

    public function memberBalanceInCents(Membership $membership): int
    {
        $totalCreditsCents = $this->toCents($membership->splitsAsCreditor()->sum('splits.amount'))
            + $this->toCents($membership->paymentsAsDebtor()->sum('amount'));

        $totalDebitsCents = $this->toCents($membership->splitsAsDebtor()->sum('splits.amount'))
            + $this->toCents($membership->paymentsAsCreditor()->sum('amount'));

        return $totalCreditsCents - $totalDebitsCents;
    }

    public function forGroup(Group $group): array
    {
        $netBalances = [];
        foreach ($group->members()->get() as $member) {
            $netBalances[$member->id] = $this->memberBalanceInCents($member);
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

    private function toCents($value): int
    {
        return (int) round(((float) $value) * 100);
    }
}
