<?php

namespace Tests\Unit;

use App\Models\TimeSlot;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeSlotTest extends TestCase
{
    use RefreshDatabase;

    public function test_time_slot_belongs_to_doctor()
    {
        $timeSlot = TimeSlot::factory()->create();

        $this->assertInstanceOf(Doctor::class, $timeSlot->doctor);
    }
}
