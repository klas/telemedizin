<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'specialization_id' => Specialization::factory(),
        ];
    }
}
