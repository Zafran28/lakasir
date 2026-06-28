<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;
use App\Filament\Tenant\Pages\Traits\HasReportPageSidebar;

class ReceivableReport extends Page
{
    use HasReportPageSidebar;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static string $view = 'filament.tenant.pages.receivable-report';

    protected static ?string $title = 'Receivable Report';

    protected static ?string $slug = 'receivable-report';

    public static function getLabel(): string
    {
        return 'Receivable Report';
    }

    public static function canAccess(): bool
    {
        return true;
    }
}