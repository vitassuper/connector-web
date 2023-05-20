<?php

namespace App\Actions;

use App\Models\Deal;

class GetPnlHistory
{
    public function execute()
    {
        return Deal::orderBy('date_close')->whereNotNull('date_close')->get(['date_close', 'pnl'])
            ->groupBy(fn (Deal $deal) => $deal->date_close->format('M-d'))
            ->map(fn ($group, $date) => $group->sum('pnl'));
    }
}
