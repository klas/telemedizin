<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\TimeSlot;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_has_specialization()
    {
        $doctor = Doctor::factory()->create();

        $this->assertInstanceOf(Specialization::class, $doctor->specialization);
    }

    public function test_doctor_has_time_slots()
    {
        $doctor = Doctor::factory()->create();
        $timeSlot = TimeSlot::factory()->create(['doctor_id' => $doctor->id]);

        $this->assertCount(1, $doctor->timeSlots);
        $this->assertInstanceOf(TimeSlot::class, $doctor->timeSlots->first());
    }

    public function test_doctor_has_appointments()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create(['doctor_id' => $doctor->id]);

        $this->assertCount(1, $doctor->appointments);
        $this->assertInstanceOf(Appointment::class, $doctor->appointments->first());
    }
}
