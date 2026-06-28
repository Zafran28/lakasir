<?php

namespace App\Filament\Tenant\Resources\FinanceTransactionResource\Pages;

use App\Filament\Tenant\Resources\FinanceTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinanceTransaction extends EditRecord
{
    protected static string $resource = FinanceTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
