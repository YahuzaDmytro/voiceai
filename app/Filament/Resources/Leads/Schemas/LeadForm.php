<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('first_name'),
                TextInput::make('last_name'),
                TextInput::make('status')
                    ->required()
                    ->default('new'),
            ]);
    }
}
