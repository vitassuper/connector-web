<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Exchange;
use App\DataObjects\ExchangeData;

class CreateExchangeAction
{
    public function execute(ExchangeData $data, User $user): Exchange
    {
        $exchange = new Exchange(['name' => $data->name, 'type' => $data->type]);

        $exchange->setApiKey($data->apiKey);
        $exchange->setApiSecret($data->apiSecret);

        $exchange->user()->associate($user);

        $exchange->save();

        return $exchange;
    }
}
