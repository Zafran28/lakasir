<?php

namespace App\Filament\Tenant\Resources;

use App\Models\Tenants\ArusKas;
use Filament\Resources\Resource;

class ArusKasResource extends Resource
{
    protected static ?string $model = ArusKas::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Arus Kas';

    protected static ?string $navigationGroup = 'Finance';
}