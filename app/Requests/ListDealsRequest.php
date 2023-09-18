<?php

namespace App\Requests;

use App\Models\Bot;
use App\Models\Deal;
use App\Base\BaseRequest;
use Illuminate\Validation\Rule;

class ListDealsRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => [
                'nullable',
                'string',
                Rule::in(['active', 'closed']),
            ],
            'bot_id' => [
                'nullable',
                'array',
            ],
            'bot_id.*' => [
                'nullable',
                'numeric',
                Rule::exists(Bot::class, 'id'),
            ],
            'pair' => [
                'nullable',
                'array',
            ],
            'pair.*' => [
                'nullable',
                'string',
                'max:64',
            ],
            'deal_id' => [
                'nullable',
                'numeric',
                Rule::exists(Deal::class, 'id'),
            ],
        ];
    }
}
