<?php

use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\InvoiceNumberController;

use App\Livewire\Forms\Auth\RegisterTenantForm;

use App\Models\Tenants\Selling;
use App\Models\Tenants\FinanceTransaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| FRONT
|--------------------------------------------------------------------------
*/
Volt::route('/', 'pages/welcome');

Route::view('/offline', 'offline');

/*
|--------------------------------------------------------------------------
| SERVICE WORKER
|--------------------------------------------------------------------------
*/
Route::get('/serviceworker.js', function () {
    return response()->file(public_path('serviceworker.js'))
        ->header('Content-Type', 'application/javascript');
});

/*
|--------------------------------------------------------------------------
| FINANCE PRINT
|--------------------------------------------------------------------------
*/
Route::get('/finance/print', [FinanceController::class, 'print'])
    ->name('finance.print');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/auth/register', RegisterTenantForm::class)
    ->name('auth.register');

/*
|--------------------------------------------------------------------------
| SURAT & KWITANSI & INVOICE NUMBER
|--------------------------------------------------------------------------
*/
Route::get('/surat-jalan/{selling}', [SuratJalanController::class, 'print'])
    ->name('surat-jalan.print');

Route::get('/kwitansi/{selling}', [KwitansiController::class, 'print'])
    ->name('kwitansi.print');

/**
 * ✅ PRINT NOMOR INVOICE (BARU)
 */
Route::get('/invoice-number/{selling}', [InvoiceNumberController::class, 'print'])
    ->name('invoice-number.print');

/*
|--------------------------------------------------------------------------
| SELLING UPDATE
|--------------------------------------------------------------------------
*/
Route::post('/selling/{selling}/update-code', function (Request $request, Selling $selling) {

    $request->validate([
        'code' => 'required|string|max:255',
    ]);

    $selling->update([
        'code' => $request->code,
    ]);

    return back();
})->name('selling.update.code');

Route::post('/selling/{selling}/update-date', function (Request $request, Selling $selling) {

    $request->validate([
        'date' => 'required|date',
    ]);

    $selling->update([
        'date' => $request->date,
    ]);

    return back();
})->name('selling.update.date');

/*
|--------------------------------------------------------------------------
| 💰 FINANCE MODULE FULL CRUD
|--------------------------------------------------------------------------
*/

/**
 * STORE
 */
Route::post('/finance/store', function (Request $request) {

    $request->validate([
        'type' => 'required|in:income,expense',
        'category' => 'required|string|max:100',
        'amount' => 'required',
        'payment_method' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:255',
    ]);

    $amount = (int) str_replace(['.', ',', ' '], '', $request->amount);

    FinanceTransaction::create([
        'transaction_no' =>
            'FT-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5)),
        'transaction_date' => now(),
        'type' => $request->type,
        'category' => $request->category,
        'amount' => $amount,
        'payment_method' => $request->payment_method ?? 'cash',
        'description' => $request->description,
        'created_by' => auth()->id(),
    ]);

    return back();
})->name('finance.store');

/**
 * UPDATE
 */
Route::post('/finance/{id}/update', function (Request $request, $id) {

    $trx = FinanceTransaction::findOrFail($id);

    $request->validate([
        'type' => 'required|in:income,expense',
        'category' => 'required|string|max:100',
        'amount' => 'required',
        'payment_method' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:255',
    ]);

    $amount = (int) str_replace(['.', ',', ' '], '', $request->amount);

    $trx->update([
        'type' => $request->type,
        'category' => $request->category,
        'amount' => $amount,
        'payment_method' => $request->payment_method,
        'description' => $request->description,
    ]);

    return back();
})->name('finance.update');

/**
 * DELETE
 */
Route::delete('/finance/{id}', function ($id) {

    $trx = FinanceTransaction::findOrFail($id);
    $trx->delete();

    return back();
})->name('finance.delete');