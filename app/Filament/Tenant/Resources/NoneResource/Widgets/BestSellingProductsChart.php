<?php

namespace App\Filament\Tenant\Resources\NoneResource\Widgets;

use App\Models\Tenants\SellingDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BestSellingProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Top  Barang Terlaris';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $products = SellingDetail::query()
            ->select(
                'product_id',
                DB::raw('SUM(qty) as total_qty')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(30)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $products->pluck('total_qty')->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $products->map(function ($item) {
                return $item->product?->name
                    ?? $item->product?->title
                    ?? 'Produk';
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}