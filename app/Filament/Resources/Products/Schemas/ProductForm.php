<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('category', 'name'),
                Select::make('brand_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('brand', 'name'),
                TextInput::make('name')
                    ->maxLength(255)
                    ->required()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->readOnly()
                    ->unique(table: 'products', column: 'slug', ignoreRecord: true)
                    ->required(),
                FileUpload::make('images')
                    ->image()
                    ->directory('products')
                    ->maxFiles(5)
                    ->reorderable()
                    ->columnSpanFull(),
                MarkdownEditor::make('description')
                    ->fileAttachmentsDirectory('products')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->columnSpanFull()
                    ->prefix('$'),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                Toggle::make('is_featured')
                    ->default(false)
                    ->required(),
                Toggle::make('in_stock')
                    ->default(true)
                    ->required(),
                Toggle::make('on_sale')
                    ->default(false)
                    ->required(),
            ]);
    }
}
