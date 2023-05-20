<?php

namespace App\Actions;

use Http;
use App\Models\Deal;

class SendCloseDealRequestAction
{
    public function execute(Deal $deal): bool
    {
        $bot = $deal->bot;

        $response = Http::post(config('connector.url'), [
            'type_of_signal' => 'close',
            'bot_id' => $bot->id,
            'connector_secret' => $bot->secret,
            'pair' => $deal->pair,
        ]);

        return $response->successful();
    }
}
