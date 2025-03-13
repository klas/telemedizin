<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_appointment()
    {
        // Create test data
        $specialization = Specialization::create(['name' => 'Allgemeinmedizin']);
        $doctor = Doctor::create(['name' => 'Dr. Schmidt', 'specialization_id' => $specialization->id]);

        $tomorrow = Carbon::tomorrow();
        $timeSlot = TimeSlot::create([
            'doctor_id' => $doctor->id,
            'start_time' => $tomorrow->copy()->setHour(9),
            'end_time' => $tomorrow->copy()->setHour(10),
            'is_available' => true
        ]);

        $appointmentTime = $tomorrow->copy()->setHour(9)->setMinute(30);

        // Test API endpoint
        $response = $this->postJson('/api/v1/appointments', [
            'doctor_id' => $doctor->id,
            'patient_name' => 'Max Mustermann',
            'patient_email' => 'max@example.com',
            'date_time' => $appointmentTime->format('Y-m-d H:i:s')
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'erfolg',
                'nachricht',
                'daten' => ['id', 'doctor_id', 'patient_name', 'patient_email', 'date_time', 'status']
            ])
            ->assertJson([
                'erfolg' => true,
                'daten' => [
                    'patient_name' => 'Max Mustermann',
                    'status' => 'geplant'
                ]
            ]);

        // Check that the time slot is now unavailable
        $this->assertDatabaseHas('time_slots', [
            'id' => $timeSlot->id,
            'is_available' => false
        ]);
    }

    public function test_can_cancel_appointment()
    {
        // Create test data
        $specialization = Specialization::create(['name' => 'Allgemeinmedizin']);
        $doctor = Doctor::create(['name' => 'Dr. Schmidt', 'specialization_id' => $specialization->id]);

        $tomorrow = Carbon::tomorrow();
        $timeSlot = TimeSlot::create([
            'doctor_id' => $doctor->id,
            'start_time' => $tomorrow->copy()->setHour(9),
            'end_time' => $tomorrow->copy()->setHour(10),
            'is_available' => false
        ]);

        $appointmentTime = $tomorrow->copy()->setHour(9)->setMinute(30);

        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_name' => 'Max Mustermann',
            'patient_email' => 'max@example.com',
            'date_time' => $appointmentTime,
            'status' => 'geplant'
        ]);

        // Test API endpoint
        $response = $this->putJson("/api/v1/appointments/{$appointment->id}/cancel");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'erfolg',
                'nachricht',
                'daten' => ['id', 'doctor_id', 'patient_name', 'patient_email', 'date_time', 'status']
            ])
            ->assertJson([
                'erfolg' => true,
                'daten' => [
                    'status' => 'storniert'
                ]
            ]);

        // Check that the time slot is now available again
        $this->assertDatabaseHas('time_slots', [
            'id' => $timeSlot->id,
            'is_available' => true
        ]);
    }
}
