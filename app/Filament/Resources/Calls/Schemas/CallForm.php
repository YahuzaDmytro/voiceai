<?php

namespace App\Filament\Resources\Calls\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CallForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lead_id')
                    ->relationship('lead', 'id')
                    ->required(),
                TextInput::make('provider'),
                TextInput::make('external_id'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('ended_at'),
                TextInput::make('duration')
                    ->numeric(),
                Textarea::make('transcript')
                    ->columnSpanFull(),
                Textarea::make('recording_url')
                    ->columnSpanFull(),
                TextInput::make('metadata'),
            ]);
    }
}
