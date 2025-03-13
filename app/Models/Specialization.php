<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property-read Collection<int, Doctor> $doctors
 * @property-read int|null $doctors_count
 * @method static Builder<static>|Specialization newModelQuery()
 * @method static Builder<static>|Specialization newQuery()
 * @method static Builder<static>|Specialization query()
 * @mixin Eloquent
 */
class Specialization extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }
}
