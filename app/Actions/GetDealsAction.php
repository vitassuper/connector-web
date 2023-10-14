<?php

namespace App\Actions;

use App\Models\Deal;
use App\Enums\SideType;
use ccxt\async\binance;
use App\Models\Exchange;
use App\Enums\DealStatus;
use function React\Async\await;
use function React\Promise\all;
use App\DataObjects\DealFiltersObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetDealsAction
{
    public function execute(DealFiltersObject $filters): LengthAwarePaginator
    {
        $deals = Deal::where(fn (Builder $query) => $this->prepareFilters($query, $filters))
            ->with(['bot.exchange', 'orders'])
            ->orderBy('date_close', 'desc')
            ->orderBy('pair')
            ->orderBy('bot_id')
            ->orderBy('position')
            ->orderBy('date_open', 'desc')
            ->paginate(50);

        $exchangeModels = $deals->getCollection()->pluck('bot.exchange')->unique()
            ->filter(fn (Exchange $exchange) => $exchange->type === Exchange::BINANCE_TYPE);

        $exchangePositions = [];
        $promises = [];

        /** @var Exchange $exchangeModel */
        foreach ($exchangeModels as $exchangeModel) {
            $exchange = new binance([
                'apiKey' => $exchangeModel->getApiKey(),
                'secret' => $exchangeModel->getApiSecret(),
                'enableRateLimit' => true,
                'options' => ['defaultType' => 'future'],
            ]);

            $promises[] = $exchange->fapiPrivateV2GetPositionRisk()->then(
                function ($res) use ($exchangeModel, &$exchangePositions) {
                    $exchangePositions[$exchangeModel->id] = $res;
                },
                function ($a) {
                }
            );
        }

        await(all($promises));

        $deals->getCollection()->each(function (Deal $deal) use ($exchangePositions) {
            $pair = str_replace('/', '', strstr($deal->pair, ':', true));

            if (null === $deal->date_close && isset($exchangePositions[$deal->bot->exchange_id])) {
                $position = collect($exchangePositions[$deal->bot->exchange_id])->first(
                    fn ($position) => $position['symbol'] === $pair && $position['positionSide'] === strtoupper($deal->bot->side->name)
                );

                $entrySum = $deal->getOpenAveragePrice() * $deal->getTotalVolume();
                $currentSum = $position['markPrice'] * $deal->getTotalVolume();

                $sign = $deal->bot->side === SideType::Long ? 1 : -1;

                $deal->uPnl = round(($currentSum - $entrySum) * $sign, 2);
                $deal->uPnlPercentage = round((((float) $position['markPrice'] - $deal->getOpenAveragePrice()) / $deal->getOpenAveragePrice()) * 100 * $sign, 2);
                $deal->exchangePnl = round(((float) $position['markPrice'] - (float) $position['entryPrice']) * $deal->getTotalVolume() * $sign, 2);
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
