<?php

namespace ValentinMorice\FilamentDonation\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SubscriptionStatusEnum: string implements HasColor, HasLabel
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::CANCELLED => 'Cancelled',
            self::OTHER => 'Other',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::CANCELLED => 'danger',
            self::OTHER => 'gray',
        };
    }
}
