<?php

namespace App\Requests;

use App\Models\Bot;
use App\Models\Exchange;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'exchange' => [
                'required',
                'numeric',
                Rule::exists(Exchange::class, 'id')
            ],
            'side' => [
                'required',
                Rule::in(array_keys(Bot::getAvailableSides()))
            ],
            'secret' => [
                'required',
                'string',
                'max:32',
            ]
        ];
    }
}
