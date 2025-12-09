<?php

namespace App\Filament\Resources\Orders\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use Illuminate\Support\Number;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New', Order::query()->where('status', 'new')->count())
                ->description('Orders placed today')
                ->color('success'),
            Stat::make('Processing', Order::query()->where('status', 'processing')->count())
                ->description('Orders being processed')
                ->color('warning'),
            Stat::make('Shipped', Order::query()->where('status', 'shipped')->count())
                ->description('Orders that have been shipped')
                ->color('info'),
            Stat::make('Delivered', Order::query()->where('status', 'delivered')->count())
                ->description('Orders delivered to customers')
                ->color('primary'),
            Stat::make('Cancelled', Order::query()->where('status', 'cancelled')->count())
                ->description('Orders that were cancelled')
                ->color('danger'),
            Stat::make('Average Value', Number::currency(Order::query()->avg('grand_total')), 'USD')
                ->description('Average value of all orders')
                ->color('secondary'),
        ];
    }
}
