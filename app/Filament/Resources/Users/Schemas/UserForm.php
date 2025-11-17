<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\CreateRecord;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->required(),

            DateTimePicker::make('email_verified_at')
                ->label('Email Verified At')
                ->default(now()),

            TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord),
        ]);
    }
}
