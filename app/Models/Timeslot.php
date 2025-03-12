<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property-read \App\Models\Doctor|null $doctor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timeslot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timeslot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timeslot query()
 * @mixin \Eloquent
 */
class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'start_time',
        'end_time',
        'is_available'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_available' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
