<?php

namespace App\Http\Controllers;

use App\Models\Tenants\Selling;

class InvoiceNumberController extends Controller
{
    public function print(Selling $selling)
    {
        return view('filament.tenant.resources.sellings.pages.view-invoice', [
            'selling' => $selling,
        ]);
    }
}