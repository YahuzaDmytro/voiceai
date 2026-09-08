<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ApplicationStatus: string implements HasLabel
{
    case New           = 'new';
    case Calling       = 'calling';
    case NoAnswer      = 'no_answer';
    case Failed        = 'failed';
    case NotInterested = 'not_interested';
    case Callback      = 'callback';
    case Converted     = 'converted';

    public function getLabel(): string
    {
        return match ($this) {
            self::New           => 'New',
            self::Calling       => 'Calling',
            self::NoAnswer      => 'No answer',
            self::Failed        => 'Failed',
            self::NotInterested => 'Not interested',
            self::Callback      => 'Callback',
            self::Converted     => 'Converted',
        };
    }
}
