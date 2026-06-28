<?php

namespace App\Filament\Tenant\Resources\FinanceTransactionResource\Pages;

use App\Filament\Tenant\Resources\FinanceTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFinanceTransaction extends CreateRecord
{
    protected static string $resource = FinanceTransactionResource::class;
}
