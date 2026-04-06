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
            $cents = round(((float) $p['amount']) * 100); // typecast
            $result[] = [
                'debtor_id' => $p['membership_id'],
                'amount' => $cents / 100,
            ];
        }

        return $result;
    }
}
