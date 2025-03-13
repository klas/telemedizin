<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'doctor_id' => Doctor::factory(),
            'patient_name' => $this->faker->name,
            'patient_email' => $this->faker->email,
            'date_time' => $this->faker->dateTime,
            'status' => 'geplant',
        ];
    }
}
