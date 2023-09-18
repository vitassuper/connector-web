<?php

namespace App\Actions;

use Http;
use App\Models\Deal;
use App\Enums\SideType;
use App\Enums\DealStatus;
use App\DataObjects\DealFiltersObject;
use Illuminate\Database\Eloquent\Builder;

class GetDealsAction
{
    public function execute(DealFiltersObject $filters)
    {
        $deals = Deal::where(fn (Builder $query) => $this->prepareFilters($query, $filters))
            ->orderBy('date_close', 'desc')
            ->orderBy('pair')
            ->orderBy('bot_id')
            ->orderBy('position')
            ->orderBy('date_open', 'desc')

            ->with(['orders'])->paginate(50);

        // TEMP SOLUTION
        $result = Http::get('https://fapi.binance.com/fapi/v1/ticker/price');
        $prices = collect(json_decode($result->body()));

        $deals->each(function ($deal) use ($prices) {
            if (null === $deal->date_close) {
                $record = $prices->firstWhere('symbol', str_replace('/', '', strstr($deal->pair, ':', true)));

                if ($record === null) {
                    $deal->uPnl = null;
                    $deal->uPnlPercentage = null;
                } else {
                    $entrySum = $deal->getOpenAveragePrice() * $deal->getTotalVolume();
                    $currentSum = $record->price * $deal->getTotalVolume();

                    $sign = $deal->bot->side === SideType::Long ? 1 : -1;

                    $deal->uPnl = round(($currentSum - $entrySum) * $sign, 2);
                    $deal->uPnlPercentage = round((($record->price - $deal->getOpenAveragePrice()) / $deal->getOpenAveragePrice()) * 100 * $sign, 2);
                }
            }
        });
        // TEMP SOLUTION

        return $deals;
    }

    private function prepareFilters(Builder $query, DealFiltersObject $filters): void
    {
        if ($botIds = $filters->botIds) {
            $query->whereHas('bot', fn (Builder $query) => $query->whereIn('id', $botIds));
        }

        if ($dealId = $filters->dealId) {
            $query->where('id', $dealId);
        }

        if ($status = $filters->status) {
            $query->where(
                fn (Builder $query) => $status === DealStatus::Active ? $query->whereNull('date_close') : $query->whereNotNull('date_close')
            );
        }

        if ($pairs = $filters->pairs) {
            $query->whereIn('pair', $pairs);
        }
    }
}
