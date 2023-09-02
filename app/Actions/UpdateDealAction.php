<?php

namespace App\Actions;

use App\Models\Deal;

class UpdateDealAction
{
    public function execute(Deal $deal, int $position): Deal
    {
        $deal->update([
            'position' => $position,
        ]);

        return $deal;
    }
}
