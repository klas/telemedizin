<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    use RequestHelper;

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email',
            'date_time' => 'required|date|after:now',
        ];
    }

    public function messages(): array
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
}
