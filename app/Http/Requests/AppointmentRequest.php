<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Public API, no authentication
    }

    public function rules()
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email',
            'date_time' => 'required|date|after:now',
        ];
    }

    public function messages()
    {
        return [
            'doctor_id.required' => 'Die Arzt-ID ist erforderlich.',
            'doctor_id.exists' => 'Der ausgewählte Arzt existiert nicht.',
            'patient_name.required' => 'Der Name des Patienten ist erforderlich.',
            'patient_email.required' => 'Die E-Mail-Adresse des Patienten ist erforderlich.',
            'patient_email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'date_time.required' => 'Datum und Uhrzeit sind erforderlich.',
            'date_time.date' => 'Bitte geben Sie ein gültiges Datum und Uhrzeit ein.',
            'date_time.after' => 'Datum und Uhrzeit müssen in der Zukunft liegen.',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'erfolg'   => false,
            'nachricht'   => 'Validation errors',
            'fehler'      => $validator->errors()
        ]));
    }
}
