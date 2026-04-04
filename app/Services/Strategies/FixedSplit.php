<?php

namespace App\Services\Strategies;

class FixedSplit implements SplitStrategy
{
    /**
     * Create a new class instance.
     */
    public function calculate($amount, $participants): array
    {

        $result = [];
        foreach ($participants as $id => $p) {
            $result[] = [
                'debtor_id' => $p['membership_id'],
                'amount' => round($p['amount']),
            ];
        }

        return $result;
    }
}
