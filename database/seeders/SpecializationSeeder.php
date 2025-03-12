<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    public function run()
    {
        $specializations = [
            'Allgemeinmedizin',
            'Kardiologie',
            'Dermatologie',
            'Neurologie',
            'Orthopädie',
            'Pädiatrie',
            'Psychiatrie',
            'Urologie',
            'Gynäkologie',
            'Onkologie'
        ];

        foreach ($specializations as $name) {
            Specialization::create(['name' => $name]);
        }
    }
}
