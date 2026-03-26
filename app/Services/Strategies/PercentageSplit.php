<?php

namespace App\Services\Strategies;

class PercentageSplit  implements SplitStrategy
{
    /**
     * Create a new class instance.
     */
    public function calculate($amount,$participants)
    {
        $result = [];
        $reyals = round(100*$amount);
        $registerReyals = 0;
        foreach($participants as $id=>$p){
            $quota= floor(($reyals*$p['percentage'])/100);
            $registerReyals += $quota;
            $result[]= [
                'debtor_id'=> $p['membership_id'],
                'amount'=> $quota/100,
            ];
        }



        if($registerReyals!==$reyals){
            $diff = $reyals-$registerReyals;
            $result[0]['amount'] +=$diff/100;
        }

        return $result;
    }

}
