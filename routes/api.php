<?php

use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\SpecializationController;
use App\Http\Controllers\API\TimeSlotController;
use App\Http\Controllers\API\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{id}', [DoctorController::class, 'show']);

    Route::get('/specializations', [SpecializationController::class, 'index']);

    Route::get('/time-slots', [TimeSlotController::class, 'getAvailableSlots']);
    Route::post('/time-slots/check-availability', [TimeSlotController::class, 'checkRealTimeAvailability']);

    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
});
