<?php

namespace App\Requests;

use App\Base\BaseRequest;

class AddSafetyOrderToDealRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric'
            ]
        ];
    }
}
