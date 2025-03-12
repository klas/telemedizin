<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('specialization')->get();
        
        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Ärzte erfolgreich abgerufen',
            'daten' => $doctors
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

    public function search(Request $request)
    {
        $query = $request->input('suche');
        
        $doctors = Doctor::with('specialization')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhereHas('specialization', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->get();
        
        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Suchergebnisse für Ärzte',
            'daten' => $doctors
        ]);
    }
}
