<?php

namespace App\Actions;

use App\Models\Bot;
use App\Models\Deal;
use App\DataObjects\DealFiltersObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GetBotsStats
{
    public function execute(DealFiltersObject $filters)
    {
        $bots = Bot::with(
            ['deals' => fn (HasMany $query) => $query->whereNull('date_close')->where(
                fn (Builder $query) => $this->prepareDealFilters($query, $filters)
            )->with(['orders', 'bot'])]
        )->whereHas('deals', fn (Builder $query) => $query->whereNull('date_close'))
            ->where(fn (Builder $query) => $this->prepareBotFilters($query, $filters))
            ->get();

        return $bots->map(
            fn (Bot $bot) => [
                'bot_id' => $bot->id,
                'bot_name' => $bot->name,
                'stats' => $bot->deals->groupBy(fn (Deal $deal) => $deal->getPairName())->map(fn ($deals) => $deals->sum(
                    fn (Deal $deal) => $deal->getTotalVolume()
                )),
            ]
        );
    }

    private function prepareDealFilters(Builder $query, DealFiltersObject $filters): void
    {
        if ($pairs = $filters->pairs) {
            $query->whereIn('pair', $pairs);
        }
    }

    private function prepareBotFilters(Builder $query, DealFiltersObject $filters): void
    {
        if ($botIds = $filters->botIds) {
            $query->whereIn('id', $botIds);
        }

        if ($pairs = $filters->pairs) {
            $query->whereHas('deals', fn (Builder $query) => $query->whereIn('pair', $pairs));
        }
    }
}
