<?php

namespace ValentinMorice\FilamentDonation\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatusEnum: string implements HasColor, HasLabel
{
    case FAILED = 'failed';

    case SUCCEEDED = 'succeeded';

    case PENDING = 'pending';

    case INCOMPLETE = 'incomplete';

    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FAILED => 'Failed',
            self::SUCCEEDED => 'Succeeded',
            self::PENDING => 'Pending',
            self::OTHER => 'Other',
            self::INCOMPLETE => 'Incomplete'
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::SUCCEEDED => 'success',
            self::PENDING => 'warning',
            self::OTHER => 'other',
            self::INCOMPLETE => 'gray',
            self::FAILED => 'danger',
        };
    }
}
