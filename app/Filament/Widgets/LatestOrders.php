<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Orders\OrderResource;

class LatestOrders extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    Protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::query())
            ->columns([
                textColumn::make('id')
                    ->label('Order ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'info',
                        'requesting' => 'primary',
                        'processing' => 'primary',
                        'shipped' => 'warning',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'new' => 'heroicon-o-plus-circle',
                        'requesting' => 'heroicon-o-clock',
                        'processing' => 'heroicon-o-cog',
                        'shipped' => 'heroicon-o-truck',
                        'delivered' => 'heroicon-o-check-circle',
                        'canceled' => 'heroicon-o-x-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->formatStateUsing(fn($state) => is_string($state) && strtolower($state) === 'cod' ? 'COD' : $state)
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                 ActionGroup::make([
                    Action::make('View Order')
                        ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
                        ->color('info')
                        ->icon('heroicon-o-eye'),
                    // DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
