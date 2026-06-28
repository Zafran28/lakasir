<?php

namespace App\Services\Tenants;

use App\Models\Tenants\About;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class CashierReportService
{
    public function generate(array $data)
    {
        $timezone = Profile::first()?->timezone ?? config('app.timezone');
        $about = About::first();

        /**
         * DATE RANGE (AMAN + STABIL)
         */
        $startDate = Carbon::parse($data['start_date'], $timezone)
            ->startOfDay()
            ->utc();

        $endDate = Carbon::parse($data['end_date'], $timezone)
            ->endOfDay()
            ->utc();

        /**
         * FIX UTAMA: pakai created_at (BUKAN date)
         */
        $sellings = Selling::query()
            ->with([
                'sellingDetails:id,selling_id,product_id,qty,price,cost,discount_price',
                'sellingDetails.product:id,name',
                'user:id,name,email',
            ])
            ->whereNotNull('created_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $reports = [];

        $totalCost = 0;
        $totalGross = 0;
        $totalNet = 0;
        $totalDiscountSelling = 0;
        $totalDiscountItem = 0;
        $totalGrossProfitAll = 0;
        $totalNetProfitAll = 0;

        foreach ($sellings as $selling) {

            $discountItem = 0;
            $grossSelling = 0;
            $netSelling = 0;
            $costSelling = 0;
            $grossProfit = 0;
            $netProfit = 0;

            $items = $selling->sellingDetails->map(function ($item) use (
                &$discountItem,
                &$grossSelling,
                &$netSelling,
                &$costSelling,
                &$grossProfit,
                &$netProfit
            ) {
                $discount = $item->discount_price ?? 0;

                $grossSelling += $item->price;
                $netSelling += ($item->price - $discount);
                $costSelling += $item->cost;

                $discountItem += $discount;

                $grossProfit += ($item->price - $item->cost);
                $netProfit += (($item->price - $discount) - $item->cost);

                return [
                    'product' => $item->product?->name,
                    'quantity' => $item->qty,

                    // harga per item (aman dari 0 qty)
                    'product_price' => Number::format($item->price / max($item->qty, 1)),
                    'product_cost' => Number::format($item->cost / max($item->qty, 1)),

                    'price' => Number::format($item->price),
                    'cost' => Number::format($item->cost),
                    'discount_price' => Number::format($discount),

                    'total_after_discount' => Number::format($item->price - $discount),

                    'gross_profit' => Number::format($item->price - $item->cost),
                    'net_profit' => Number::format(($item->price - $discount) - $item->cost),
                ];
            });

            $reports[] = [
                'id' => $selling->id,

                /**
                 * FIX: pakai created_at (SOURCE OF TRUTH)
                 */
                'created_at' => Carbon::parse($selling->created_at)
                    ->setTimezone($timezone)
                    ->format('d F Y H:i'),

                'number' => $selling->code,
                'user' => $selling->user?->name ?? $selling->user?->email,

                'transaction' => [
                    'items' => $items,
                ],

                'total' => [
                    'cost' => Number::format($costSelling),
                    'discount' => Number::format($discountItem),
                    'gross_selling' => Number::format($grossSelling),
                    'net_selling' => Number::format($netSelling),
                    'discount_selling' => Number::format($selling->discount_price ?? 0),
                    'total_gross_profit' => Number::format($grossProfit),
                    'total_net_profit' => Number::format($netProfit),
                    'grand_total' => Number::format($netSelling - ($selling->discount_price ?? 0)),
                ],
            ];

            $totalCost += $costSelling;
            $totalGross += $grossSelling;
            $totalNet += $netSelling;
            $totalDiscountSelling += ($selling->discount_price ?? 0);
            $totalDiscountItem += $discountItem;
            $totalGrossProfitAll += $grossProfit;
            $totalNetProfitAll += $netProfit;
        }

        return [
            'reports' => $reports,
            'footer' => [
                'total_cost' => Number::format($totalCost),
                'total_gross' => Number::format($totalGross),
                'total_net' => Number::format($totalNet),
                'total_discount' => Number::format($totalDiscountSelling),
                'total_discount_per_item' => Number::format($totalDiscountItem),
                'total_gross_profit' => Number::format($totalGrossProfitAll),
                'total_net_profit_before_discount_selling' => Number::format($totalGrossProfitAll),
                'total_net_profit_after_discount_selling' => Number::format($totalNetProfitAll),
            ],
            'header' => [
                'shop_name' => $about?->shop_name,
                'shop_location' => $about?->shop_location,
                'business_type' => $about?->business_type,
                'owner_name' => $about?->owner_name,
                'start_date' => Carbon::parse($data['start_date'], $timezone)->format('d F Y'),
                'end_date' => Carbon::parse($data['end_date'], $timezone)->format('d F Y'),
            ],
        ];
    }
}