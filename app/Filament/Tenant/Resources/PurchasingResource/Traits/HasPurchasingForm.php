<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Traits;

use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Support\RawJs;

trait HasPurchasingForm
{
    public function get($product = 'stocks.product'): array
    {
        return [
            Select::make('product_id')
                ->translateLabel()
                ->native(false)
                ->placeholder(__('Search...'))
                ->relationship(name: $product, titleAttribute: 'name')

                // FIX: barcode dihapus biar tidak error kalau kolom belum konsisten
                ->searchable(['name', 'sku'])

                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state) {

                    $product = Product::find($state);

                    if ($product) {
                        $set('initial_price', $product->initial_price ?? 0);
                        $set('selling_price', $product->selling_price ?? 0);
                    } else {
                        $set('initial_price', 0);
                        $set('selling_price', 0);
                    }
                }),

            ...Stock::form(),

            TextInput::make('total_initial_price')
                ->prefix(Setting::get('currency', 'IDR'))
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly()
                ->default(0),

            TextInput::make('total_selling_price')
                ->live()
                ->prefix(Setting::get('currency', 'IDR'))
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly()
                ->default(0),
        ];
    }
}