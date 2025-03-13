<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RequestHelper
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'erfolg'   => false,
            'nachricht'   => 'Validation errors',
            'fehler'      => $validator->errors()
        ], 422));
    }
}
