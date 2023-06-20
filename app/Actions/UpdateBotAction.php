<?php

namespace App\Actions;

use App\Models\Bot;
use App\DataObjects\BotData;

class UpdateBotAction
{
    private GetCopyBotSettingsAction $getCopyBotSettingsAction;

    public function __construct(GetCopyBotSettingsAction $getCopyBotSettingsAction)
    {
        $this->getCopyBotSettingsAction = $getCopyBotSettingsAction;
    }

    public function execute(Bot $bot, BotData $data): Bot
    {
        if ($data->copyBotId) {
            $data = $this->getCopyBotSettingsAction->execute($data);
        }

        $bot->name = $data->name;
        $bot->exchange_id = $data->exchangeId;
        $bot->copy_bot_id = $data->copyBotId;
        $bot->secret = $data->secret;
        $bot->side = $data->side;

        $bot->save();

        return $bot;
    }
}
