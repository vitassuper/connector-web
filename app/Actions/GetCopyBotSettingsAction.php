<?php

namespace App\Actions;

use App\Models\Bot;
use App\DataObjects\BotData;

class GetCopyBotSettingsAction
{
    public function execute(BotData $data): BotData
    {
        $copyBot = Bot::find($data->copyBotId);

        $data->secret = $copyBot->secret;
        $data->side = $copyBot->side;

        return $data;
    }
}
