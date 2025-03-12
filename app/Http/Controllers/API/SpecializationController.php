<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Specialization;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::all();
        
        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Fachgebiete erfolgreich abgerufen',
            'daten' => $specializations
        ]);
    }
}
