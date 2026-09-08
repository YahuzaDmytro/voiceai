<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LeadStatus: string implements HasLabel
{
    case Qualified       = 'qualified';
    case ReadyForManager = 'ready_for_manager';

    public function getLabel(): string
    {
        return match ($this) {
            self::Qualified       => 'Qualified',
            self::ReadyForManager => 'Ready for manager',
        };
    }
}
