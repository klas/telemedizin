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
            'success' => true,
            'message' => 'Fachgebiete erfolgreich abgerufen',
            'data' => SpecializationResource::collection($specializations)
        ]);
    }
}
