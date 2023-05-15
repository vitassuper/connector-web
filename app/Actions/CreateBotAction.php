<?php

namespace App\Actions;

use Str;
use Hash;
use App\Models\Bot;
use App\DataObjects\BotData;

class CreateBotAction
{
    private UpdateBotAction $updateBotAction;

    public function __construct(UpdateBotAction $updateBotAction)
    {
        $this->updateBotAction = $updateBotAction;
    }

    public function execute(BotData $data): Bot
    {
        $bot = new Bot();
        $bot->secret_token = Str::random(32);

        return $this->updateBotAction->execute($bot, $data);
    }
}
