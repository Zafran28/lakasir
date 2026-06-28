<?php

namespace App\Filament\Tenant\Resources\YesResource\Widgets;

use App\Models\Tenants\Selling;
use Filament\Widgets\ChartWidget;

class SellingChartTahunan extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan';

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        return Selling::query()
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year', 'year')
            ->toArray();
    }

    protected function getData(): array
    {
        $year = $this->filter ?? now()->year;

        $sales = Selling::query()
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $sales[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => "Penjualan Tahun {$year}",
                    'data' => $data,
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}