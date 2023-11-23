<?php

namespace App\Actions;

use GuzzleHttp\Client;

class GetKlinesForRangeAction
{
    //TODO: add chunks for api request
    public function execute(string $symbol, int $startRange, int $endRange)
    {
        $klines = collect();

        while ($startRange < $endRange) {
            $endLocalRange = $startRange + (500 * 60);

            $result = collect($this->sendRequest($symbol, $startRange, $endLocalRange));
            $klines = $klines->merge($result->map(fn ($candlestickData) => ['volume' => $candlestickData[5], 'open_time' => $candlestickData[0] / 1000]));

            $startRange = $endLocalRange;
        }

        return $klines;
    }

    private function sendRequest(string $symbol, int $startRange, int $endRange)
    {
        $client = new Client();

        $response = $client->request('GET', 'https://fapi.binance.com/fapi/v1/klines', [
            'query' => [
                'interval' => '1m',
                'limit' => 500,
                'symbol' => $symbol,
                'startTime' => $startRange * 1000,
                'endTime' => $endRange * 1000,
            ],
        ]);

        $body = $response->getBody();

        return json_decode($body, true);
    }
}
