<?php

namespace App\Actions;

use App\Models\Bot;
use App\Enums\ExchangeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetBotsAction
{
    public function execute(?ExchangeType $exchangeType = null): Collection|array
    {
        return Bot::when($exchangeType, fn (Builder $query) => $query->whereHas(
            'exchange',
            fn (Builder $query) => $query->where('type', $exchangeType)
        ))->get();
    }
}
