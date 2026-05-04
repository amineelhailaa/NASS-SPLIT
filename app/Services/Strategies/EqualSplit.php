<?php

namespace App\Services\Strategies;

class EqualSplit implements SplitStrategy
{
    /**
     * Create a new class instance.
     */
    public function calculate($amount, $participants): array
    {
        $result = [];
        $reyals = round(100 * $amount);
        $tarif = floor($reyals / count($participants));
        $reste = $reyals % count($participants);
        foreach ($participants as $i => $p) {
            $quota = $tarif;
            if ($i < $reste) {
                $quota += 1;
            }
            $result[] = [
                'debtor_id' => $p['membership_id'],
                'amount' => $quota / 100,
            ];
        }

        return $result;
    }
}
