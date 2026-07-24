<?php

namespace App\Http\Controllers;

use App\Models\Tenants\About;
use App\Models\Tenants\Selling;
use Barryvdh\DomPDF\Facade\Pdf;

class KwitansiController extends Controller
{
    /**
     * Preview HTML
     */
    public function print(Selling $selling)
    {
        $selling->load('sellingDetails.product', 'user');

        $about = About::first();

        return view('reports.kwitansi.print', compact(
            'selling',
            'about'
        ));
    }

    /**
     * Download PDF
     */
    public function download(Selling $selling)
    {
        $selling->load('sellingDetails.product', 'user');

        $about = About::first();

        $pdf = Pdf::loadView('reports.kwitansi.print', [
            'selling' => $selling,
            'about'   => $about,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Kwitansi-'.$selling->code.'.pdf');
    }
}