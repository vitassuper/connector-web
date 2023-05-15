<?php

namespace App\DataObjects;

use App\Requests\CreateBotRequest;

class BotData
{
    public string $name;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
    }

    public static function createFromRequest(CreateBotRequest $request): BotData
    {
        return new self($request->validated());
    }
}
