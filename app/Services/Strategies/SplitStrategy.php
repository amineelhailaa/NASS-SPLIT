<?php

namespace App\Services\Strategies;

interface SplitStrategy
{

    public function calculate($amount,$participants);
}
