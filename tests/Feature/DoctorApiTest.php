<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_doctors()
    {
        $doctors = Doctor::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/doctors');

        $response->assertStatus(200);
        $response->assertJson([
            'erfolg' => true,
            'nachricht' => 'Ärzte erfolgreich abgerufen',
            'daten' => $doctors->toArray(),
        ]);
    }

    public function test_can_get_doctors_by_search_query()
    {
        $doctor1 = Doctor::factory()->create(['name' => 'John Doe']);
        $doctor2 = Doctor::factory()->create(['name' => 'Jane Doe']);

        $response = $this->getJson('/api/v1/doctors?search=Doe');

        $response->assertStatus(200);
        $response->assertJson([
            'erfolg' => true,
            'nachricht' => 'Ärzte erfolgreich abgerufen',
            'daten' => [$doctor1->toArray(), $doctor2->toArray()],
        ]);
    }

    public function test_can_get_doctors_by_specialization()
    {
        $specialization = Specialization::factory()->create(['name' => 'Cardiology']);
        $doctor = Doctor::factory()->create(['specialization_id' => $specialization->id]);

        $response = $this->getJson('/api/v1/doctors?search=Cardiology');

        $response->assertStatus(200);
        $response->assertJson([
            'erfolg' => true,
            'nachricht' => 'Ärzte erfolgreich abgerufen',
            'daten' => [$doctor->toArray()],
        ]);
    }

    public function test_can_get_single_doctor()
    {
        $doctor = Doctor::factory()->create();

        $response = $this->getJson('/api/v1/doctors/' . $doctor->id);

        $response->assertStatus(200);
        $response->assertJson([
            'erfolg' => true,
            'nachricht' => 'Arzt erfolgreich abgerufen',
            'daten' => $doctor->toArray(),
        ]);
    }

    public function test_returns_404_if_doctor_not_found()
    {
        $response = $this->getJson('/api/v1/doctors/999');

        $response->assertStatus(404);
        $response->assertJson([
            'erfolg' => false,
            'nachricht' => 'Arzt nicht gefunden',
        ]);
    }
}
