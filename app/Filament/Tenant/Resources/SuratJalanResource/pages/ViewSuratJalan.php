<?php

namespace App\Filament\Tenant\Resources\SuratJalanResource\Pages;

use App\Filament\Tenant\Resources\SuratJalanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSuratJalan extends ViewRecord
{
    protected static string $resource = SuratJalanResource::class;

    public function getView(): string
    {
        return 'filament.tenant.resources.surat-jalan.pages.view-surat-jalan';
    }
}