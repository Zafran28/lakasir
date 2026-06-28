<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            margin: 30px;
            font-size: 14px;
            color: #000;
        }

        .title{
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td{
            border: 1px solid #000;
            padding: 8px;
        }

        .no-border td{
            border: none;
            padding: 4px;
        }

        .product-table th{
            text-align: center;
            font-weight: bold;
        }

        .product-table td{
            vertical-align: top;
        }

        .center{
            text-align: center;
        }

        .note{
            margin-top: 20px;
        }

        .signature{
            margin-top: 70px;
        }

        .signature td{
            border: none;
            text-align: center;
            vertical-align: top;
        }

        .space-sign{
            height: 100px;
        }

        .print-button{
            margin-bottom: 20px;
        }

        @media print{
            .print-button{
                display: none;
            }

            body{
                margin: 20px;
            }
        }
    </style>
</head>
<body>

<button class="print-button" onclick="window.print()">
    Print
</button>

<div class="flex justify-between items-center mb-3">

    {{-- LOGO --}}
    <div>
        <img
            src="{{ asset('storage/product/Trinovasi.png') }}"
            style="width:180px; height:auto; display:block;"
        >
    </div>

    {{-- TITLE --}}
    <div style="text-align:right; width:100%;">

        <h1 style="
            font-size:32px;
            font-weight:bold;
            letter-spacing:3px;
            margin:0;
        ">
            SURAT JALAN
        </h1>

        <div style="
            border-bottom:1px solid #999;
            width:180px;
            margin-left:auto;
            margin-top:6px;
        "></div>

    </div>

</div>

<table style="width:100%; border-collapse:collapse; margin-bottom:20px;">

    <tr>

        <!-- KIRI -->
        <td width="50%" style="
            border:1px solid #000;
            padding:12px;
            vertical-align:top;
        ">

            <div style="margin-bottom:6px;">
                <strong>To</strong>
                :
                {{ $selling->member->name ?? '-' }}
            </div>

            <div class="text-gray-700">
                {{ $selling->member?->address ?? 'N/A' }}
            </div>
            
        </td>

        <!-- KANAN -->
        <td width="50%" style="
            border:1px solid #000;
            padding:12px;
            vertical-align:top;
        ">

            <div style="margin-bottom:6px;">
                <strong>Nomor Surat Jalan</strong>
                :
                {{ str_replace('/INV/', '/SJ/', $selling->code) }}
            </div>

            <div>
                <strong>Tanggal</strong>
                :
                {{ \Carbon\Carbon::parse($selling->date)->format('d M Y') }}
            </div>

        </td>

    </tr>

</table>

<table class="product-table">
    <thead>
        <tr>
            <th width="50">No</th>
            <th>Nama Barang</th>
            <th width="150">Banyaknya</th>
            <th width="250">Keterangan</th>
        </tr>
    </thead>

    <tbody>

    @foreach($selling->sellingDetails as $key => $item)
        <tr>
            <td class="center">
                {{ $key + 1 }}
            </td>

            <td>
                {{ $item->product->name ?? '-' }}
            </td>

            <td class="center">
                    {{ number_format($item->qty, 0, ',', '.') }}
                    {{ $item->product->unit ?? '' }}
            </td>
            <td>
                Dalam kondisi baik/baru
            </td>
        </tr>
    @endforeach

    </tbody>
</table>

<div class="note">
    Harap diterima dengan baik berupa :
</div>

<table class="signature">
    <tr>
        <td>
            Yang menyerahkan
        </td>

        <td>
            Yang menerima
        </td>
    </tr>

    <tr>
        <td class="space-sign"></td>
        <td class="space-sign"></td>
    </tr>

    <tr>
        <td>
            Nama dan stampel
        </td>

        <td>
            Nama dan stampel
        </td>
    </tr>
</table>
{{-- FOOTER --}}
<div class="footer" style="
    margin-top:70px;
    text-align:center;
    font-size:12px;
    line-height:1.7;
    padding-top:10px;
">

    <div style="
        font-weight:bold;
        font-size:13px;
        letter-spacing:1px;
    ">
        PT TRINOVASI DIGITAL SOLUSI
    </div>

    <div>
        Jl. Raya Pasar Jengkol No. 39 (BSD – Parung),
        Babakan Setu, Tangerang Selatan Banten
    </div>

    <div>
        Telp. 021-75874606 · 085286083882
        Email : digitalsolusitrinovasi@gmail.com
    </div>

</div>
<script>
    window.onload = function () {
        window.print();
    }
</script>

</body>
</html>