<?php

namespace App\Requests;

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
            ]
        ];
    }
}
