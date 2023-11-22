<?php

namespace App\Actions;

use GuzzleHttp\Client;

class GetKlinesForRangeAction
{
    //TODO: add chunks for api request
    public function execute($symbol, $startRange, $endRange)
    {
        $count = 0;
        $interval = 5 * 60;

        for ($time = $startRange; $time < $endRange; $time += $interval) {
            $count++;
        }

        $client = new Client();

        $response = $client->request('GET', 'https://fapi.binance.com/fapi/v1/klines', [
            'query' => [
                'interval' => '5m',
                'symbol' => $symbol,
                'startTime' => $startRange * 1000,
                'endTime' => $endRange * 1000,
            ],
        ]);

        $body = $response->getBody();

        return json_decode($body, true);
    }
}
