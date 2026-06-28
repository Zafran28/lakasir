<?php

namespace App\Http\Controllers;

use App\Models\Tenants\About;
use App\Models\Tenants\Selling;

class SuratJalanController extends Controller
{
    public function print(Selling $selling)
    {
        $selling->load('sellingDetails.product');

        $about = About::first();

        return view('surat-jalan.print', compact(
            'selling',
            'about'
        ));
    }
}