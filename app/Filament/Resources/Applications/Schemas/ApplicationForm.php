<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Services\ConversationService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ApplicationForm
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
                Select::make('status')
                    ->options(ApplicationStatus::class)
                    ->required()
                    ->default(ApplicationStatus::New),
                Textarea::make('summary')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('conversation_log')
                    ->label('AI conversation')
                    ->disabled()
                    ->dehydrated(false)
                    ->rows(12)
                    ->columnSpanFull()
                    ->afterStateHydrated(function (Textarea $component, ?Application $record): void {
                        if ($record === null) {
                            return;
                        }

                        $component->state(app(ConversationService::class)->transcriptText($record) ?: 'No messages yet.');
                    }),
            ]);
    }
}
