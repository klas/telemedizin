<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property-read Collection<int, Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read Specialization|null $specialization
 * @property-read Collection<int, TimeSlot> $timeSlots
 * @property-read int|null $time_slots_count
 * @method static Builder<static>|Doctor newModelQuery()
 * @method static Builder<static>|Doctor newQuery()
 * @method static Builder<static>|Doctor query()
 * @mixin Eloquent
 */
class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialization_id'];

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }
}
