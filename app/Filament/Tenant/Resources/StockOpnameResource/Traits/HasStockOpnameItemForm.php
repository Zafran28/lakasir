<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Traits;

use App\Models\Tenants\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

trait HasStockOpnameItemForm
{
    public function get($relation = 'stockOpnameItems.product'): array
    {
        return [
            Select::make('product_id')
                ->label(__('Product'))
                ->required()
                ->native(false)
                ->placeholder(__('Search...'))
                ->relationship(name: $relation, titleAttribute: 'name')

                // ❌ HAPUS barcode & sku (biar tidak error)
                ->searchable(['name'])

                ->preload()
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state) {
                    $product = Product::find($state);

                    if ($product) {
                        $set('current_stock', $product->stock);
                    }
                }),

            TextInput::make('current_stock')
                ->label(__('Current stock'))
                ->readOnly()
                ->numeric(),

            Select::make('adjustment_type')
                ->label(__('Adjustment type'))
                ->default('broken')
                ->live()
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                    $product = Product::find($get('product_id'));

                    if (! $product) {
                        Notification::make()
                            ->title(__('Please select the product first'))
                            ->warning()
                            ->send();

                        return;
                    }

                    $actual = (int) $get('actual_stock');

                    $set('missing_stock', $product->stock - $actual);
                })
                ->options([
                    'broken' => __('Broken'),
                    'lost' => __('Lost'),
                    'expired' => __('Expired'),
                    'manual_input' => __('Manual Input'),
                ]),

            TextInput::make('actual_stock')
                ->label(__('Actual stock'))
                ->required()
                ->numeric()
                ->live(onBlur: true)
                ->disabled(fn (Get $get) => ! $get('adjustment_type'))
                ->afterStateUpdated(function (Set $set, Get $get, $state) {
                    $product = Product::find($get('product_id'));

                    if (! $product) {
                        Notification::make()
                            ->title(__('Please select the product first'))
                            ->warning()
                            ->send();

                        return;
                    }

                    $set('missing_stock', $product->stock - (int) $state);
                }),

            TextInput::make('missing_stock')
                ->label(__('Missing stock'))
                ->readOnly()
                ->numeric(),

            FileUpload::make('attachment')
                ->label(__('Attachment'))
                ->disk(config('filesystems.upload_disk'))
                ->image()
                ->maxSize(1024 * 2),
        ];
    }
}