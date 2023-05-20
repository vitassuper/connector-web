<?php

namespace App\Requests;

use App\Models\Bot;
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
