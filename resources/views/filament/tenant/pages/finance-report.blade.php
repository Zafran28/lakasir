<x-filament::page>

<style>
/* =========================
   NORMAL VIEW
========================= */
.print-header {
    display: none;
}

/* =========================
   PRINT STYLE FULL FIX
========================= */
@media print {

    /* =========================
       PAGE SETUP LANDSCAPE
    ========================= */
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    /* =========================
       RESET TOTAL BACKGROUND
       (FIX KOTAK HITAM)
    ========================= */
    * {
        background: transparent !important;
        box-shadow: none !important;
    }

    body {
        background: #fff !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* =========================
       FORCE WHITE CLEAN UI
    ========================= */
    .bg-white,
    .bg-gray-50,
    .bg-gray-100,
    .bg-gray-200,
    .bg-gray-300,
    .bg-gray-400,
    .dark\:bg-gray-800,
    .dark\:bg-gray-900 {
        background: #fff !important;
    }

    /* =========================
       HIDE FILAMENT UI
    ========================= */
    .fi-header,
    .fi-topbar,
    .fi-sidebar,
    .fi-ta-header-toolbar,
    .fi-ta-search-field,
    .fi-input-wrapper,
    .fi-tabs,
    nav,
    header,
    aside,
    button,
    .no-print {
        display: none !important;
    }

    /* =========================
       CLEAN CARD / BOX
    ========================= */
    .rounded-xl,
    .rounded-lg,
    .border,
    .shadow,
    .shadow-sm,
    .ring,
    .ring-1 {
        background: #fff !important;
        border-color: #000 !important;
        box-shadow: none !important;
    }

    /* =========================
       TABLE FIX
    ========================= */
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        background: #fff !important;
    }

    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    th, td {
        border: 1px solid #000 !important;
        padding: 6px;
        font-size: 11px;
        color: #000 !important;
        background: #fff !important;
        white-space: nowrap;
    }

    th {
        font-weight: bold;
        border-bottom: 2px solid #000 !important;
    }

    /* =========================
       PRINT HEADER
    ========================= */
    .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 10px;
        color: #000 !important;
    }
}

/* =========================
   CATEGORY WRAP FIX
========================= */
.category {
    white-space: normal;
    max-width: 180px;
}
</style>

@php
    $transactions = \App\Models\Tenants\FinanceTransaction::latest()->get();

    $totalIncome = $transactions->where('type', 'income')->sum('amount');
    $totalExpense = $transactions->where('type', 'expense')->sum('amount');
    $balance = $totalIncome - $totalExpense;

    $categories = [
        'receivable_payment' => 'Piutang Usaha Masuk',
        'accounts_payable' => 'Utang Usaha',
        'accounts_receivable' => 'Piutang Usaha',
        'purchase' => 'Pembelian',
        'salary' => 'Gaji',
        'other_expense' => 'Pengeluaran Lain-lain',
        'transportation' => 'Transportasi',
        'electricity' => 'Listrik',
        'office_supply' => 'ATK & Perlengkapan',
        'orphan_support' => 'Santunan Anak Yatim',
    ];
@endphp

{{-- =========================
     HEADER BUTTON
========================= --}}
<div class="flex justify-between items-center mb-4 no-print">

    <h2 class="text-2xl font-bold">
        Finance Report
    </h2>

    <button
        type="button"
        onclick="window.print()"
        class="px-4 py-2 rounded-lg bg-primary-600 text-white shadow"
    >
        🖨 Print Report
    </button>

</div>

{{-- =========================
     PRINT HEADER
========================= --}}
<div class="print-header">
    <h1 style="font-size:20px;font-weight:bold;">
        FINANCE REPORT
    </h1>

    <p>
        Printed: {{ now()->format('d M Y H:i') }}
    </p>

    <hr style="margin-top:10px;">
</div>

{{-- =========================
     SUMMARY CARDS
========================= --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 no-print">

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="text-sm text-gray-500">Total Pemasukan</div>
        <div class="mt-2 text-3xl font-bold text-green-600">
            IDR {{ number_format($totalIncome, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="text-sm text-gray-500">Total Pengeluaran</div>
        <div class="mt-2 text-3xl font-bold text-red-600">
            IDR {{ number_format($totalExpense, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="text-sm text-gray-500">Saldo Bersih</div>
        <div class="mt-2 text-3xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
            IDR {{ number_format($balance, 0, ',', '.') }}
        </div>
    </div>

</div>

{{-- =========================
     TABLE
========================= --}}
<div class="bg-white rounded-xl border shadow-sm mt-6">

    <div class="px-6 py-4 border-b no-print">
        <h2 class="font-semibold text-lg">
            Finance Transactions
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50">

                <tr>
                    <th>Transaction No</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th class="text-right">Amount</th>
                    <th class="text-center">Method</th>
                    <th>Description</th>
                    <th class="text-center">Date</th>
                </tr>

            </thead>

            <tbody>

                @forelse($transactions as $transaction)

                    <tr class="border-t hover:bg-gray-50">

                        <td>{{ $transaction->transaction_no }}</td>

                        <td>
                            @if($transaction->type === 'income')
                                <span class="text-green-600 font-semibold">Income</span>
                            @else
                                <span class="text-red-600 font-semibold">Expense</span>
                            @endif
                        </td>

                        <td class="category">
                            {{ $categories[$transaction->category] ?? ucwords(str_replace('_', ' ', $transaction->category)) }}
                        </td>

                        <td class="text-right font-semibold">
                            {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>

                        <td class="text-center">
                            {{ ucfirst($transaction->payment_method) }}
                        </td>

                        <td>
                            {{ $transaction->description }}
                        </td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-500">
                            No transaction found
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

</x-filament::page>