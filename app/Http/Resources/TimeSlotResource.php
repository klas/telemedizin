<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeSlotResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_available' => $this->is_available
        ];
    }
}
