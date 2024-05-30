<?php

namespace ValentinMorice\FilamentDonation\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ValentinMorice\FilamentDonation\Models\Donor
 *
 * @property string $phone_code
 */
class Donor extends Model
{
    protected $guarded = [];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: function (mixed $_, array $attributes) {
                return $attributes['first_name'] . ' ' . $attributes['last_name'];
            }
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
