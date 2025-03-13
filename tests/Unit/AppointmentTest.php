<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\TimeSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_belongs_to_doctor()
    {
        $appointment = Appointment::factory()->create();

        $this->assertInstanceOf(Doctor::class, $appointment->doctor);
    }
}
