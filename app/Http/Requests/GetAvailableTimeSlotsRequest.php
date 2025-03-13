<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAvailableTimeSlotsRequest extends FormRequest
{
    use RequestHelper;

    public function rules()
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'datum' => 'date',
        ];
    }

    public function messages()
    {
        return [
            'doctor_id.required' => 'Die Arzt-ID ist erforderlich.',
            'doctor_id.exists' => 'Der ausgewählte Arzt existiert nicht.',
            'datum.date' => 'Bitte geben Sie ein gültiges Datum ein.',
        ];
    }
}
