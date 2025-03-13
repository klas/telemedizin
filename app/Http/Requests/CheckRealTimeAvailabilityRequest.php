<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckRealTimeAvailabilityRequest extends FormRequest
{
    use RequestHelper;

    public function rules(): array
    {
        return [
            'time_slot_id' => 'required|exists:time_slots,id',
        ];
    }

    public function messages(): array
    {
        return [
            'time_slot_id.required' => 'Die Terminzeit-ID ist erforderlich.',
            'time_slot_id.exists' => 'Die ausgewählte Terminzeit existiert nicht.',
        ];
    }
}
