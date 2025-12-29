<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use App\Models\Product;


class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Customer')
                    ->relationship('user', 'name'),
                Select::make('payment_method')
                    ->required()
                    ->options([
                        'stripe' => 'Stripe',
                        'COD' => 'Cash on Delivery',
                    ]),
                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required(),
                ToggleButtons::make('status')
                    ->options([
                        'new' => 'New',
                        'requesting' => 'Requesting',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->colors([
                        'new' => 'info',
                        'requesting' => 'primary',
                        'processing' => 'primary',
                        'shipped' => 'warning',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                    ])
                    ->icons([
                        'new' => 'heroicon-o-plus-circle',
                        'processing' => 'heroicon-o-cog',
                        'shipped' => 'heroicon-o-truck',
                        'delivered' => 'heroicon-o-check-circle',
                        'canceled' => 'heroicon-o-x-circle',
                    ])
                    ->inline()
                    ->default('new')
                    ->required(),
                Select::make('currency')
                    ->required()
                    ->options([
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                        'VND' => 'VND',
                    ])
                    ->default('USD'),
                Select::make('shipping_method')
                    ->options([
                        'standard' => 'Standard Shipping',
                        'express' => 'Express Shipping',
                    ])
                    ->default('standard'),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull()
                    ->placeholder('Additional notes about the order...'),

                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->reactive()
                            ->columnSpan(3)
                            ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Product::find($state)?->price ?? 0))
                            ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Product::find($state)?->price ?? 0)),

                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(1)
                            ->reactive()
                            ->columnSpan(3)
                            ->afterStateUpdated(fn ($state, Set $set, $get) => $set('total_amount', $get('unit_amount') * $state)),

                        TextInput::make('unit_amount')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->columnSpan(3),

                        TextInput::make('total_amount')
                            ->numeric()
                            ->columnSpan(3)
                            ->required(),
                    ])
                    ->columns(12)
                    ->columnSpanFull(),

                    Placeholder::make('grand_total_placeholder')
                        ->label('Grand Total')
                        ->content(function (Get $get, Set $set) {
                            $total = 0;
                            $items = $get('items') ?? [];
                            foreach ($items as $item) {
                                $total += $item['total_amount'] ?? 0;
                            }
                            $set('grand_total', $total);
                            return number_format($total, 2) . ' USD';
                        }),

                    Hidden::make('grand_total')
                        ->default(0),
                ]

            );

    }
}
