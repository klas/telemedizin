<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeSlotController extends Controller
{
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'datum' => 'date',
        ], [
            'doctor_id.required' => 'Die Arzt-ID ist erforderlich.',
            'doctor_id.exists' => 'Der ausgewählte Arzt existiert nicht.',
            'datum.date' => 'Bitte geben Sie ein gültiges Datum ein.',
        ]);
        
        $doctorId = $request->input('doctor_id');
        $date = $request->has('datum') 
            ? Carbon::parse($request->input('datum'))->startOfDay() 
            : Carbon::now()->startOfDay();
        
        $timeSlots = TimeSlot::where('doctor_id', $doctorId)
            ->where('is_available', true)
            ->whereDate('start_time', '>=', $date)
            ->orderBy('start_time')
            ->get();
        
        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Verfügbare Termine erfolgreich abgerufen',
            'daten' => $timeSlots
        ]);
    }

    public function checkRealTimeAvailability(Request $request)
    {
        $request->validate([
            'time_slot_id' => 'required|exists:time_slots,id',
        ], [
            'time_slot_id.required' => 'Die Terminzeit-ID ist erforderlich.',
            'time_slot_id.exists' => 'Die ausgewählte Terminzeit existiert nicht.',
        ]);
        
        $timeSlotId = $request->input('time_slot_id');
        
        // Use a transaction to prevent race conditions
        \DB::beginTransaction();
        
        try {
            $timeSlot = TimeSlot::lockForUpdate()->find($timeSlotId);
            
            $isAvailable = $timeSlot && $timeSlot->is_available;
            
            \DB::commit();
            
            return response()->json([
                'erfolg' => true,
                'nachricht' => $isAvailable ? 'Termin ist verfügbar' : 'Termin ist nicht mehr verfügbar',
                'daten' => [
                    'ist_verfuegbar' => $isAvailable
                ]
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            
            return response()->json([
                'erfolg' => false,
                'nachricht' => 'Fehler bei der Überprüfung der Verfügbarkeit',
                'fehler' => $e->getMessage()
            ], 500);
        }
    }
}
