<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Fachbereiche
        $specializations = [
            'Allgemeinmedizin',
            'Innere Medizin',
            'Dermatologie',
            'Kardiologie',
            'Psychologie'
        ];

        foreach ($specializations as $name) {
            Specialization::create(['name' => $name]);
        }

        // Ärzte
        $doctors = [
            ['name' => 'Dr. Julia Schmidt', 'specialization_id' => 1],
            ['name' => 'Dr. Thomas Meyer', 'specialization_id' => 1],
            ['name' => 'Dr. Anna Weber', 'specialization_id' => 2],
            ['name' => 'Dr. Michael Becker', 'specialization_id' => 3],
            ['name' => 'Dr. Sabine Müller', 'specialization_id' => 4],
            ['name' => 'Dr. Frank Schulz', 'specialization_id' => 5]
        ];

        foreach ($doctors as $doctorData) {
            Doctor::create($doctorData);
        }

        // Zeitfenster für die kommenden 7 Tage erstellen
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Für jeden der nächsten 7 Tage
            for ($day = 1; $day <= 7; $day++) {
                $date = Carbon::now()->addDays($day);

                // Falls Wochenende, überspringen
                if ($date->isWeekend()) {
                    continue;
                }

                // Zeitfenster für 9 bis 17 Uhr, jeweils 30 Minuten
                for ($hour = 9; $hour < 17; $hour++) {
                    for ($minute = 0; $minute < 60; $minute += 30) {
                        $startTime = (clone $date)->setHour($hour)->setMinute($minute)->setSecond(0);
                        $endTime = (clone $startTime)->addMinutes(30);

                        TimeSlot::create([
                            'doctor_id' => $doctor->id,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'is_available' => true
                        ]);
                    }
                }
            }
        }
    }
}
