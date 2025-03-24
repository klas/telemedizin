<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetAvailableTimeSlotsRequest;
use App\Http\Resources\TimeSlotResource;
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
        // Validate the request
        $validated = $request->validated();

        $doctorId = $validated['doctor_id'];
        $date = $request->has('datum')
            ? Carbon::parse($validated['datum'])->startOfDay()
            : Carbon::now()->startOfDay();

        $timeSlots = TimeSlot::where('doctor_id', $doctorId)
            ->where('is_available', true)
            ->whereDate('start_time', '>=', $date)
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Verfügbare Zeitfenster erfolgreich abgerufen',
            'data' => TimeSlotResource::collection($timeSlots)
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
                'success' => $isAvailable,
                'message' => $isAvailable ? 'Zeitfenster ist verfügbar' : 'Zeitfenster ist nicht mehr verfügbar'
            ], $isAvailable ? 200 : 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Fehler bei der Überprüfung der Verfügbarkeit',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
