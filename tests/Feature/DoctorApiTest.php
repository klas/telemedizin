<?php

namespace Tests\Feature;

use App\Http\Resources\DoctorResource;
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

        $doctors = DoctorResource::collection($doctors)->resolve();

        foreach ($doctors as &$data) {
            $data['specialization'] = $data['specialization']->resolve();
        }

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Ärzte erfolgreich abgerufen',
            'data' => $doctors
        ]);
    }

    public function test_can_get_doctors_by_search_query()
    {
        $doctor1 = Doctor::factory()->create(['name' => 'John Doe']);
        $doctor2 = Doctor::factory()->create(['name' => 'Jane Doe']);

        $response = $this->getJson('/api/v1/doctors?search=Doe');

        $response->assertStatus(200);

        $daten1 = (new DoctorResource($doctor1))->resolve();
        $daten1['specialization'] = $daten1['specialization']->resolve();

        $daten2 = (new DoctorResource($doctor2))->resolve();
        $daten2['specialization'] = $daten2['specialization']->resolve();

        $response->assertJson([
            'success' => true,
            'message' => 'Ärzte erfolgreich abgerufen',
            'data' => [$daten1, $daten2],
        ]);
    }

    public function test_can_get_doctors_by_specialization()
    {
        $specialization = Specialization::factory()->create(['name' => 'Cardiology']);
        $doctor = Doctor::factory()->create(['specialization_id' => $specialization->id]);

        $response = $this->getJson('/api/v1/doctors?search=Cardiology');

        $response->assertStatus(200);
        $daten = (new DoctorResource($doctor))->resolve();
        $daten['specialization'] = $daten['specialization']->resolve();

        $response->assertJson([
            'success' => true,
            'message' => 'Ärzte erfolgreich abgerufen',
            'data' => [$daten]
        ]);
    }

    public function test_can_get_single_doctor()
    {
        $doctor = Doctor::factory()->create();

        $response = $this->getJson('/api/v1/doctors/' . $doctor->id);

        $response->assertStatus(200);
        $daten = (new DoctorResource($doctor))->resolve();
        $daten['specialization'] = $daten['specialization']->resolve();

        $response->assertJson([
            'success' => true,
            'message' => 'Arzt erfolgreich abgerufen',
            'data' => $daten
        ]);
    }

    public function test_returns_404_if_doctor_not_found()
    {
        $response = $this->getJson('/api/v1/doctors/999');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Not Found !',
        ]);
    }
}
