<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                 TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(table: 'categories', column: 'slug', ignoreRecord: true)
                    ->required()
                    ->readOnly(),

                FileUpload::make('image')
                    ->image()
                    ->directory('categories')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
