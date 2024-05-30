<?php

namespace ValentinMorice\FilamentDonation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ValentinMorice\FilamentDonation\Enums\PaymentStatusEnum;

class Payment extends Model
{
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
        'status' => PaymentStatusEnum::class,
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }
}
