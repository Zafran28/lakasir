<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;
use App\Filament\Tenant\Pages\Traits\HasReportPageSidebar;

class FinanceReport extends Page
{
    use HasReportPageSidebar;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static string $view = 'filament.tenant.pages.finance-report';

    protected static ?string $title = 'Finance Report';

    public static function getLabel(): string
    {
        return 'Finance Report';
    }

    public static function canAccess(): bool
    {
        return true;
    }
}