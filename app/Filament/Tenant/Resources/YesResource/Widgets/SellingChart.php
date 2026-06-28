<?php

namespace App\Filament\Tenant\Resources\YesResource\Widgets;

use App\Models\Tenants\Receivable;
use Filament\Widgets\ChartWidget;

class SellingChart extends ChartWidget
{
    protected static ?string $heading = 'Receivables Overview (Paid vs Unpaid)';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Total Paid (status = 1)
        $paid = Receivable::query()
            ->where('status', 1)
            ->sum('total_receivable');

        // Total Unpaid (status = 0, ambil sisa tagihan)
        $unpaid = Receivable::query()
            ->where('status', 0)
            ->sum('rest_receivable');

        return [
            'datasets' => [
                [
                    'data' => [
                        (int) ($paid ?? 0),
                        (int) ($unpaid ?? 0),
                    ],
                    'backgroundColor' => [
                        '#22c55e', // hijau (paid)
                        '#ef4444', // merah (unpaid)
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => [
                'Paid',
                'Unpaid',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}