<?php

namespace App\DataObjects;

use App\Enums\DealStatus;
use App\Requests\ListDealsRequest;

class DealFiltersObject
{
    public array $pairs = [];
    public array $botIds = [];
    public ?DealStatus $status;
    public ?int $dealId;

    public function __construct(array $data)
    {
        $this->botIds = $data['bot_id'] ?? [];
        $this->pairs = $data['pair'] ?? [];
        $this->status = isset($data['status']) ? DealStatus::from($data['status']) : null;
        $this->dealId = $data['deal_id'] ?? null;
    }

    public static function fromRequest(ListDealsRequest $request): DealFiltersObject
    {
        return new self($request->validated());
    }
}
