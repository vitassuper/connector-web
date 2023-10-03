<?php

namespace App\Actions\Deals;

use Http;

class GetCurPricesFromBinanceAction
{
    public function execute()
    {
        $result = Http::get('https://fapi.binance.com/fapi/v1/ticker/price');

        return collect(json_decode($result->body()));
    }
}
