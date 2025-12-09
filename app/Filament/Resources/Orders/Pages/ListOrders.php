<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Orders\Widgets\OrderStats;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('all')
                ->label('All')
                ->query(fn ($query) => $query),

            Tab::make('new')
                ->label('New')
                ->query(fn ($query) => $query->where('status', 'new')),

            Tab::make('processing')
                ->label('Processing')
                ->query(fn ($query) => $query->where('status', 'processing')),

            Tab::make('shipped')
                ->label('Shipped')
                ->query(fn ($query) => $query->where('status', 'shipped')),

            Tab::make('delivered')
                ->label('Delivered')
                ->query(fn ($query) => $query->where('status', 'delivered')),

            Tab::make('cancelled')
                ->label('Cancelled')
                ->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }
}
