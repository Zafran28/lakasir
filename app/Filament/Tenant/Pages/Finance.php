<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenants\FinanceTransaction;
use Filament\Pages\Page;

class Finance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static string $view = 'filament.tenant.pages.finance';
    protected static ?string $title = 'Finance';

    /**
     * ACCESS CONTROL (INI YANG KAMU PERBAIKI)
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // 1. OWNER selalu full akses
        if ($user->is_owner == 1) {
            return true;
        }

        // 2. Role-based access
        if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
            return true;
        }

        // 3. Permission-based access (kalau kamu pakai Spatie Permission)
        if ($user->can('view finance')) {
            return true;
        }

        return false;
    }

    /**
     * GET TRANSACTIONS
     */
    public function getTransactions()
    {
        return FinanceTransaction::latest()->get();
    }

    /**
     * TOTAL INCOME
     */
    public function getTotalIncome()
    {
        return FinanceTransaction::where('type', 'income')
            ->sum('amount');
    }

    /**
     * TOTAL EXPENSE
     */
    public function getTotalExpense()
    {
        return FinanceTransaction::where('type', 'expense')
            ->sum('amount');
    }

    /**
     * BALANCE
     */
    public function getBalance()
    {
        return $this->getTotalIncome() - $this->getTotalExpense();
    }
}