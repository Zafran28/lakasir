<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Tenant\Resources\YesResource\Widgets\SellingChart;
use App\Filament\Tenant\Resources\YesResource\Widgets\SellingChartTahunan;
use App\Filament\Tenant\Resources\NoneResource\Widgets\BestSellingProductsChart;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            SellingChart::class,
            SellingChartTahunan::class,
            BestSellingProductsChart::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 1;
    }
}