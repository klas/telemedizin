<?php

namespace Tests\Unit;

use App\Models\Specialization;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecializationTest extends TestCase
{
    use RefreshDatabase;

    public function test_specialization_has_doctors()
    {
        $specialization = Specialization::factory()->create();
        $doctor = Doctor::factory()->create(['specialization_id' => $specialization->id]);

        $this->assertCount(1, $specialization->doctors);
        $this->assertInstanceOf(Doctor::class, $specialization->doctors->first());
    }
}
