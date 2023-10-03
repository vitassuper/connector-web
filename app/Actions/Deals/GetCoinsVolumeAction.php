<?php

namespace App\Actions\Deals;

use App\Models\Deal;

class GetCoinsVolumeAction
{
    public function execute()
    {
        $openedDeals = Deal::whereNull('date_close')->get();

        return $openedDeals->groupBy('pair')->map(fn ($deals) => $deals->sum(fn (Deal $deal) => $deal->getTotalVolume()));
    }
}
