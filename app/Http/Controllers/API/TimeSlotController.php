<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetAvailableTimeSlotsRequest;
use App\Http\Requests\CheckRealTimeAvailabilityRequest;
use App\Models\TimeSlot;
use Carbon\Carbon;

class TimeSlotController extends Controller
{
    public function getAvailableSlots(GetAvailableTimeSlotsRequest $request)
    {
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

    /**
     * @throws \Throwable
     */
    public function checkRealTimeAvailability(CheckRealTimeAvailabilityRequest $request)
    {
        $timeSlotId = $request->input('time_slot_id');

        // Use a transaction to prevent race conditions
        \DB::beginTransaction();

        try {
            $timeSlot = TimeSlot::lockForUpdate()->find($timeSlotId);

            $isAvailable = $timeSlot && $timeSlot->is_available;

            \DB::commit();

            return response()->json([
                'erfolg' => $isAvailable,
                'nachricht' => $isAvailable ? 'Termin ist verfügbar' : 'Termin ist nicht mehr verfügbar'
            ], $isAvailable ? 200 : 422);
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
