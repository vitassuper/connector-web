<?php

namespace App\DataObjects;

use App\Requests\CreateBotRequest;

class BotData
{
    public string $name;
    public int $side;
    public string $secret;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->side = $data['side'];
        $this->secret = $data['secret'];
    }

    public static function createFromRequest(CreateBotRequest $request): BotData
    {
        return new self($request->validated());
    }
}
