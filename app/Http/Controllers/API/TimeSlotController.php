<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetAvailableTimeSlotsRequest;
use App\Http\Requests\CheckRealTimeAvailabilityRequest;
use App\Models\TimeSlot;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class TimeSlotController extends Controller
{
    public function getAvailableSlots(GetAvailableTimeSlotsRequest $request): JsonResponse
    {
        $doctorId = $request->integer('doctor_id');
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
     * @throws Throwable
     */
    public function checkRealTimeAvailability(int $id): JsonResponse
    {
        // Use a transaction to prevent race conditions
        DB::beginTransaction();

        try {
            $timeSlot = TimeSlot::lockForUpdate()->find($id);
            $isAvailable = $timeSlot && $timeSlot->is_available;

            DB::commit();

            return response()->json([
                'erfolg' => $isAvailable,
                'nachricht' => $isAvailable ? 'Termin ist verfügbar' : 'Termin ist nicht mehr verfügbar'
            ], $isAvailable ? 200 : 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'erfolg' => false,
                'nachricht' => 'Fehler bei der Überprüfung der Verfügbarkeit',
                'fehler' => $e->getMessage()
            ], 500);
        }
    }
}
