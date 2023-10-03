<?php

namespace App\Actions;

use App\Models\Deal;
use App\Enums\SideType;
use App\Enums\DealStatus;
use App\DataObjects\DealFiltersObject;
use Illuminate\Database\Eloquent\Builder;
use App\Actions\Deals\GetCoinsVolumeAction;
use App\Actions\Deals\GetCurPricesFromBinanceAction;

class GetDealsAction
{
    private GetCoinsVolumeAction $getCoinsVolumeAction;
    private GetCurPricesFromBinanceAction $getCurPricesFromBinanceAction;

    public function __construct(GetCoinsVolumeAction $getCoinsVolumeAction, GetCurPricesFromBinanceAction $getCurPricesFromBinanceAction)
    {
        $this->getCoinsVolumeAction = $getCoinsVolumeAction;
        $this->getCurPricesFromBinanceAction = $getCurPricesFromBinanceAction;
    }

    public function execute(DealFiltersObject $filters)
    {
        $deals = Deal::where(fn (Builder $query) => $this->prepareFilters($query, $filters))
            ->orderBy('date_close', 'desc')
            ->orderBy('pair')
            ->orderBy('bot_id')
            ->orderBy('position')
            ->orderBy('date_open', 'desc')

            ->with(['orders'])->paginate(50);

        $prices = $this->getCurPricesFromBinanceAction->execute();
        $coinsVolume = $this->getCoinsVolumeAction->execute();

        $deals->each(function ($deal) use ($prices, $coinsVolume) {
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

                    $exchangeCurrentSum = $record->price * $coinsVolume->get($deal->pair);
                    $exchangeEntrySum = $deal->getOpenAveragePrice() * $coinsVolume->get($deal->pair);
                    $deal->exchangePnl = round(($exchangeCurrentSum - $exchangeEntrySum) * $sign, 2);
                }
            }
        });

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
