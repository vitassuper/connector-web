<?php

namespace App\DataObjects;

use App\Enums\DealStatus;
use App\Requests\ListDealsRequest;

class DealFiltersObject
{
    public ?string $pair;
    public ?int $botId;
    public ?DealStatus $status;
    public ?int $dealId;

    public function __construct(array $data)
    {
        $this->botId = $data['bot_id'] ?? null;
        $this->pair = $data['pair'] ?? null;
        $this->status = isset($data['status']) ? DealStatus::from($data['status']) : null;
        $this->dealId = $data['deal_id'] ?? null;
    }

    public static function fromRequest(ListDealsRequest $request): DealFiltersObject
    {
        return new self($request->validated());
    }
}
