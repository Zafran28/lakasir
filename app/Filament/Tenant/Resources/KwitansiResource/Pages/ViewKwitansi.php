<?php

namespace App\Filament\Tenant\Resources\KwitansiResource\Pages;

use App\Filament\Tenant\Resources\KwitansiResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKwitansi extends ViewRecord
{
    protected static string $resource = KwitansiResource::class;

    public function getView(): string
    {
        return 'filament.tenant.resources.kwitansi.pages.view-kwitansi';
    }
}