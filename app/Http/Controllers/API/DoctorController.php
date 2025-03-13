<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with('specialization');

        if ($query = $request->input('search')) {
            $doctors = $doctors->where('name', 'LIKE', "%{$query}%")
                ->orWhereHas('specialization', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                });
        }

        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Ärzte erfolgreich abgerufen',
            'daten' => $doctors->get()
        ]);
    }

    public function show($id)
    {
        $doctor = Doctor::with('specialization')->find($id);

        if (!$doctor) {
            return response()->json([
                'erfolg' => false,
                'nachricht' => 'Arzt nicht gefunden'
            ], 404);
        }

        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Arzt erfolgreich abgerufen',
            'daten' => $doctor
        ]);
    }
}
