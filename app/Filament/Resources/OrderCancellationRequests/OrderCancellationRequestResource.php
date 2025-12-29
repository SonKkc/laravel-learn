<?php

namespace App\Filament\Resources\OrderCancellationRequests;

use App\Models\OrderCancellationRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Icons\Heroicon;

class OrderCancellationRequestResource extends Resource
{
    protected static ?string $model = OrderCancellationRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ExclamationCircle;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('order.id')->label('Order')->formatStateUsing(fn($state, $record) => $record->order ? '#'.$record->order->id : '-'),
                TextColumn::make('user.email')->label('User')->sortable()->searchable(),
                TextColumn::make('reason')->label('Reason')->limit(80),
                BadgeColumn::make('status')->colors(['success' => 'approved', 'danger' => 'rejected', 'warning' => 'requested'])->label('Status'),
                TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
            ])
            ->filters([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderCancellationRequests::route('/'),
            'view' => Pages\ViewOrderCancellationRequest::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'requested')->count();
        return $count ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $count = static::getModel()::where('status', 'requested')->count();
        return $count > 0 ? 'danger' : null;
    }
}
