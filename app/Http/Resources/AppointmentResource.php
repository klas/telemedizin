<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'doctor' => new DoctorResource($this->doctor),
            'time_slot' => new TimeSlotResource($this->timeSlot),
            'patient_name' => $this->patient_name,
            'patient_email' => $this->patient_email,
            'status' => $this->status
        ];
    }
}
