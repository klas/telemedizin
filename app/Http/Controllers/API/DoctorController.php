<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $doctors = Doctor::with('specialization');

        if ($query = $request->input('search')) {
            $doctors = $doctors->where('name', 'LIKE', "%$query%")
                ->orWhereHas('specialization', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                });
        }

        return response()->json([
            'success' => true,
            'message' => 'Ärzte erfolgreich abgerufen',
            'data' => DoctorResource::collection($doctors->get())
        ]);
    }


    public function show(int $id): JsonResponse
    {
        $doctor = Doctor::with('specialization')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Arzt erfolgreich abgerufen',
            'data' => new DoctorResource($doctor)
        ]);
    }
}
