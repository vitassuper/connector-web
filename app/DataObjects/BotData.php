<?php

namespace App\DataObjects;

use App\Enums\SideType;
use App\Requests\CreateBotRequest;

class BotData
{
    public string $name;
    public ?SideType $side;
    public ?string $secret;
    public int $exchangeId;
    public ?int $copyBotId;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->exchangeId = $data['exchange'];

        $this->side = isset($data['side']) ? SideType::from($data['side']) : null;
        $this->secret = $data['secret'] ?? null;
        $this->copyBotId = $data['copy_bot_id'] ?? null;
    }

    public static function createFromRequest(CreateBotRequest $request): BotData
    {
        return new self($request->validated());
    }
}
