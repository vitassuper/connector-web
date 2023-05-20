<?php

namespace App\Actions;

use App\Models\Bot;
use App\DataObjects\BotData;

class UpdateBotAction
{
    public function execute(Bot $bot, BotData $data): Bot
    {
        $bot->fill([
            'name' => $data->name,
            'side' => $data->side,
            'secret' => $data->secret,
        ]);

        $bot->save();

        return $bot;
    }
}
