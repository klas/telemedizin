<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeSlotFactory extends Factory
{
    protected $model = TimeSlot::class;

    public function definition()
    {
        return [
            'doctor_id' => Doctor::factory(),
            'start_time' => $this->faker->dateTime,
            'end_time' => $this->faker->dateTime,
            'is_available' => true,
        ];
    }
}
