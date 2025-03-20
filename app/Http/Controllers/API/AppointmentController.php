<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\TimeSlot;
use App\Mail\AppointmentConfirmation;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Log;
use Throwable;

class AppointmentController extends Controller
{
    public function index(): JsonResponse
    {
        $appointments = Appointment::with('doctor.specialization')->get();

        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Termine erfolgreich abgerufen',
            'daten' => AppointmentResource::collection($appointments)
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $appointment = Appointment::with('doctor.specialization')->find($id);

        if (!$appointment) {
            return response()->json([
                'erfolg' => false,
                'nachricht' => 'Termin nicht gefunden'
            ], 404);
        }

        return response()->json([
            'erfolg' => true,
            'nachricht' => 'Termin erfolgreich abgerufen',
            'daten' => new AppointmentResource($appointment)
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(AppointmentRequest $request): JsonResponse
    {
        // Begin transaction to ensure data consistency
        DB::beginTransaction();

        // Validate the request
        $validated = $request->validated();

        try {
            // Check if the time slot is available
            $timeSlot = TimeSlot::where('doctor_id', $validated['doctor_id'] )
                ->where('start_time', '<=', $validated['date_time'])
                ->where('end_time', '>=', $validated['date_time'])
                ->where('is_available', true)
                ->lockForUpdate()
                ->first();

            if (!$timeSlot) {
                DB::rollBack();
                return response()->json([
                    'erfolg' => false,
                    'nachricht' => 'Der gewählte Termin ist nicht verfügbar'
                ], 422);
            }

            // Create the appointment
            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'patient_name' => $validated['patient_name'],
                'patient_email' => $validated['patient_email'],
                'date_time' => $validated['date_time'],
                'status' => 'geplant'
            ]);

            // Mark the time slot as unavailable
            $timeSlot->is_available = false;
            $timeSlot->save();

            DB::commit();

            // Send confirmation email
            $this->sendAppointmentConfirmation($appointment);

            return response()->json([
                'erfolg' => true,
                'nachricht' => 'Termin erfolgreich erstellt',
                'daten' => new AppointmentResource($appointment)
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'erfolg' => false,
                'nachricht' => 'Fehler beim Erstellen des Termins',
                'fehler' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @throws Throwable
     */
    public function cancel(int $id): JsonResponse
    {
        // Begin transaction to ensure data consistency
        DB::beginTransaction();

        try {
            $appointment = Appointment::findOrFail($id);

            if ($appointment->status === 'storniert') {
                DB::rollBack();
                return response()->json([
                    'erfolg' => false,
                    'nachricht' => 'Dieser Termin wurde bereits storniert'
                ], 422);
            }

            // Update appointment status
            $appointment->status = 'storniert';
            $appointment->save();

            // Make the time slot available again
            $timeSlot = TimeSlot::where('doctor_id', $appointment->doctor_id)
                ->where('start_time', '<=', $appointment->date_time)
                ->where('end_time', '>=', $appointment->date_time)
                ->first();

            if ($timeSlot) {
                $timeSlot->is_available = true;
                $timeSlot->save();
            }

            DB::commit();

            return response()->json([
                'erfolg' => true,
                'nachricht' => 'Termin erfolgreich storniert',
                'daten' => new AppointmentResource($appointment)
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'erfolg' => false,
                'nachricht' => 'Fehler beim Stornieren des Termins',
                'fehler' => $e->getMessage()
            ], 500);
        }
    }

    protected function sendAppointmentConfirmation($appointment): bool
    {
        try {
            // For a real application, this would send an actual email
            // For this example, we'll just simulate it
            // Mail::to($appointment->patient_email)->send(new AppointmentConfirmation($appointment));

            // Log instead for simulation
            Log::info('Terminbestätigungsmail gesendet an: ' . $appointment->patient_email);

            return true;
        } catch (Exception $e) {
            Log::error('Fehler beim Senden der Terminbestätigungsmail: ' . $e->getMessage());
            return false;
        }
    }
}
