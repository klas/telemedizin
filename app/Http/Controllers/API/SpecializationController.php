<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpecializationResource;
use App\Models\Specialization;
use Illuminate\Http\JsonResponse;

class SpecializationController extends Controller
{
    public function index(): JsonResponse
    {
        $specializations = Specialization::all();

        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Fachgebiete erfolgreich abgerufen',
            'daten' => SpecializationResource::collection($specializations)
        ]);
    }
}
