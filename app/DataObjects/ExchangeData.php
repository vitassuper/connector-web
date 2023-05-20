<?php

namespace App\DataObjects;

use App\Requests\CreateExchangeRequest;

class ExchangeData
{
    public string $name;
    public string $type;
    public string $apiKey;
    public string $apiSecret;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->apiKey = $data['api_key'];
        $this->apiSecret = $data['api_secret'];
    }

    final public static function fromRequests(CreateExchangeRequest $request): ExchangeData
    {
        return new self($request->validated());
    }
}
