<?php

namespace App\Http\Controllers;

use App\Models\Tenants\FinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    public function index()
    {
        $transactions = FinanceTransaction::latest()->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('filament.tenant.pages.finance', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
        ]);

        FinanceTransaction::create([
            'transaction_no' => 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'transaction_date' => now(),
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Transaction created');
    }

    // ✅ PRINT METHOD
    public function print()
    {
        $transactions = FinanceTransaction::latest()->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('finance.print', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }
}