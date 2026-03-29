<?php

namespace App\Services;

use App\Services\Strategies\EqualSplit;
use App\Services\Strategies\FixedSplit;
use App\Services\Strategies\PercentageSplit;

class StrategyManagerService
{
    /**
     * Create a new class instance.
     */

    public function dataToInsert($splitStrategy, $amount, $participants): array
    {
        return $this->whichStrategy($splitStrategy)->calculate($amount, $participants);
    }


    public function whichStrategy(string $splitStrategy){
        switch ($splitStrategy){

            case 'percentage':
                $strategy=  new PercentageSplit();
                break;
            case 'fixed':
                $strategy = new FixedSplit();
                break;

            default :
                $strategy = new EqualSplit();
                break;
        }
        return $strategy;
    }
}
