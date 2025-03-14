<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Psy\debug;

class TimeSlotApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_available_time_slots()
    {
        // Create test data
        $specialization = Specialization::create(['name' => 'Allgemeinmedizin']);
        $doctor = Doctor::create(['name' => 'Dr. Schmidt', 'specialization_id' => $specialization->id]);

        $tomorrow = Carbon::tomorrow();
        TimeSlot::create([
            'doctor_id' => $doctor->id,
            'start_time' => $tomorrow->copy()->setHour(9),
            'end_time' => $tomorrow->copy()->setHour(10),
            'is_available' => true
        ]);

        // Test API endpoint
        $response = $this->getJson("/api/v1/time-slots?doctor_id=$doctor->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'erfolg',
                'nachricht',
                'daten' => [
                    '*' => ['id', 'doctor_id', 'start_time', 'end_time', 'is_available']
                ]
            ])
            ->assertJson(['erfolg' => true]);
    }


    public function test_can_check_real_time_availability()
    {
        $timeSlot = TimeSlot::factory()->create(['is_available' => true]);

        $response = $this->getJson('/api/v1/time-slots/check-availability/' . $timeSlot->id);

        $response->assertStatus(200);
        $response->assertJson([
            'erfolg' => true,
            'nachricht' => 'Termin ist verfügbar',
        ]);
    }

    public function test_if_time_slot_not_found()
    {
        $timeSlot = TimeSlot::factory()->create(['is_available' => false]);
dump($timeSlot->id);
        $response = $this->getJson('/api/v1/time-slots/check-availability/' . $timeSlot->id);

        $response->assertStatus(422);
        $response->assertJson([
            'erfolg' => false,
            'nachricht' => 'Termin ist nicht mehr verfügbar',
        ]);
    }
}
