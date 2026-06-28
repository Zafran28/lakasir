<?php

namespace App\Filament\Tenant\Resources\ReceivableResource\Widgets;

use App\Models\Tenants\Member;
use App\Models\Tenants\Receivable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsReceivable extends BaseWidget
{
    protected function getStats(): array
    {
        $receivable = Receivable::query()
            ->select(
                DB::raw('SUM(total_receivable) as total_receivable'),
                DB::raw('SUM(rest_receivable) as rest_receivable')
            )
            ->first();

        $member = Member::query()
            ->whereHas('receivables')
            ->count();

        // helper format juta (M)
        $toMillion = function ($value) {
            return number_format(($value ?? 0) / 1000000, 2) . ' M';
        };

        return [
            Stat::make(
    'Total Tagihan',
    $toMillion($receivable->total_receivable)
),

Stat::make(
    'Sisa Tagihan',
    $toMillion($receivable->rest_receivable)
),
            Stat::make(
                __('Member'),
                $member
            ),
        ];
    }
}