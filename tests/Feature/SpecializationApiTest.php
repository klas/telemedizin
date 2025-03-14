<?php

namespace Tests\Feature;

use App\Http\Resources\SpecializationResource;
use App\Models\Specialization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecializationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_specializations()
    {
        $specializations = Specialization::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/specializations');

        $response->assertStatus(200);
        $response->assertJson([
            'erfolg' => true,
            'nachricht' => 'Fachgebiete erfolgreich abgerufen',
            'daten' => SpecializationResource::collection($specializations)->resolve(),
        ]);
    }
}
