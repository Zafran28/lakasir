<?php

namespace App\Http\Controllers;

use App\Models\Tenants\About;
use App\Models\Tenants\Selling;

class KwitansiController extends Controller
{
    public function print(Selling $selling)
    {
        $selling->load('sellingDetails.product', 'user');

        $about = About::first();

        return view('reports.kwitansi.print', compact(
            'selling',
            'about'
        ));
    }
}