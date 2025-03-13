<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null                                                               $appointments_count
 * @property-read \App\Models\Specialization|null                                        $specialization
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeSlot>    $timeSlots
 * @property-read int|null                                                               $time_slots_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor query()
 * @mixin \Eloquent
 */
class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialization_id'];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
