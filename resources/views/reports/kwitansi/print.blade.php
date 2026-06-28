<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            margin:12px;
            font-size:13px;
            color:#000;
        }

        .print-button{
            margin-bottom:10px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table td{
            border:1px solid #000;
            padding:8px;
            vertical-align:top;
        }

        @media print{
            .print-button{
                display:none;
            }

            body{
                margin:5px;
            }
        }
    </style>
</head>
<body>

<button class="print-button" onclick="window.print()">
    Print
</button>

<!-- WRAPPER BESAR -->
<!-- WRAPPER BESAR -->
<div style="
    display:flex;
    align-items:stretch;
    gap:0;
    width:100%;

    margin-top:60px;
">
    <!-- KOTAK KIRI -->
<div style="
    width:145px;
    border:1px solid #000;
    padding:8px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:flex-start;
">

    <img
        src="{{ asset('storage/product/Trinovasi.png') }}"
        style="
            width:110px;
            height:auto;
            display:block;
            margin-bottom:8px;
        "
    >

    <p style="
        font-size:9px;
        line-height:1.3;
        margin:0;
        text-align:center;
    ">
        Jl. Raya Pasar Jengkol No. 39 (BSD – Parung),<br>
        Babakan Setu, Tangerang Selatan Banten<br><br>

        Telp. 021-75874606<br>
        085286083882<br><br>

        Email :<br>
        digitalsolusitrinovasi@gmail.com
    </p>

</div>

    <!-- BAGIAN KANAN -->
    <div style="
        flex:1;
    ">

        <!-- HEADER -->
        <table style="
            margin-bottom:0;
        ">

            <tr>

                <!-- JUDUL -->
                <td width="50%">

                    <div style="
                        text-align:center;
                        font-size:26px;
                        font-weight:bold;
                        letter-spacing:1px;
                    ">
                        KWITANSI
                    </div>

                </td>

                <!-- NOMOR -->
                <td width="50%">

                    <div>
                        <strong>Nomor Kwitansi</strong>
                        :
                        {{ str_replace('/INV/', '/KW/', $selling->code) }}
                    </div>

                </td>

            </tr>

        </table>

        <!-- ISI -->
        <div style="
            border-left:1px solid #000;
            border-right:1px solid #000;
            border-bottom:1px solid #000;

            padding:10px;
            line-height:1.4;
        ">

            <!-- TELAH TERIMA -->
            <div style="
                display:flex;
                margin-bottom:8px;
            ">

                <div style="
                    width:190px;
                    font-weight:bold;
                ">
                    Telah terima dari
                </div>

                <div style="
                    width:20px;
                    text-align:center;
                    font-weight:bold;
                ">
                    :
                </div>

                <div style="
                    flex:1;
                ">
                    Bendahara Pengeluaran {{ $selling->member->name ?? '-' }}
                </div>

            </div>

            <!-- UANG SEJUMLAH -->
            <div style="
                display:flex;
                margin-bottom:8px;
            ">

                <div style="
                    width:190px;
                    font-weight:bold;
                ">
                    Uang Sejumlah
                </div>

                <div style="
                    width:20px;
                    text-align:center;
                    font-weight:bold;
                ">
                    :
                </div>

                <div style="
                    flex:1;
                    font-size:15px;
                    font-weight:bold;
                    text-transform:capitalize;
                ">
                    {{ ucfirst(trim(terbilang((int) $selling->total_price))) }} Rupiah
                </div>

            </div>

            <!-- UNTUK PEMBAYARAN -->
            <div style="
                display:flex;
                align-items:flex-start;
            ">

                <div style="
                    width:190px;
                    font-weight:bold;
                ">
                    Untuk Pembayaran
                </div>

                <div style="
                    width:20px;
                    text-align:center;
                    font-weight:bold;
                ">
                    :
                </div>

                <div
                    contenteditable="true"
                    style="
                        flex:1;
                        border:1px dashed #999;
                        padding:5px;
                        min-height:90px;

                        font-family:Arial, sans-serif;
                        font-size:13px;
                        line-height:1.4;

                        white-space:pre-wrap;
                        word-break:break-word;
                        outline:none;
                    "
                >Belanja Alat/Bahan untuk Kegiatan Kantor-Kertas dan Cover
                  Sesuai Kontrak Nomor 000.3.2/I/06.38/PPK/PL.BJ/2026,
                  Tanggal 09 Januari 2026 dan
                  Sesuai Pesanan Barang/Jasa
                  Nomor 000.3.2/BJ/306/RSUDSayang/01/2026,
                  Tanggal 09 Januari 2026</div>

            </div>

            <!-- FOOTER -->
            <div style="
                margin-top:20px;
                display:flex;
                justify-content:space-between;
                align-items:flex-start;
            ">

                <!-- KIRI -->
                <div style="width:45%;">

                    <div style="
                        display:flex;
                        align-items:center;
                        gap:8px;
                    ">

                        <div style="
                            font-size:16px;
                            font-weight:bold;
                        ">
                            Rp.
                        </div>

                        <div style="
                            border:1px solid #000;
                            width:180px;
                            height:38px;

                            display:flex;
                            align-items:center;

                            padding-left:10px;

                            font-size:18px;
                            font-weight:bold;
                        ">
                            {{ number_format($selling->total_price, 0, ',', '.') }}
                        </div>

                    </div>

                </div>

                <!-- KANAN -->
                <!-- KANAN -->
<div style="
    width:40%;
    text-align:center;
">

    <div style="
        font-weight:bold;
        margin-bottom:15px;
    ">
        Trinovasi Digital Solusi, PT
    </div>

    <!-- AREA MATERAI -->
    <div style="
        height:95px;
    ">
        <!-- Tempat Materai -->
    </div>

    <div style="
        font-weight:bold;
        text-decoration:underline;
    ">
        Gilang Setia Darmawan
    </div>

    <div>
        Direktur
    </div>

</div>

            </div>

        </div>

    </div>

</div>

<script>
document.querySelectorAll('[contenteditable="true"]').forEach(el => {

    el.addEventListener('paste', function(e) {

        e.preventDefault();

        let text = (e.clipboardData || window.clipboardData)
            .getData('text/plain');

        text = text.replace(/\n\s*\n/g, '\n');

        document.execCommand('insertText', false, text);

    });

});
</script>

@php

function terbilang($angka)
{
    $angka = abs($angka);

    $huruf = [
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas'
    ];

    if ($angka < 12) {
        return ' ' . $huruf[$angka];
    }

    if ($angka < 20) {
        return terbilang($angka - 10) . ' belas';
    }

    if ($angka < 100) {
        return terbilang(floor($angka / 10)) . ' puluh' . terbilang($angka % 10);
    }

    if ($angka < 200) {
        return ' seratus' . terbilang($angka - 100);
    }

    if ($angka < 1000) {
        return terbilang(floor($angka / 100)) . ' ratus' . terbilang($angka % 100);
    }

    if ($angka < 2000) {
        return ' seribu' . terbilang($angka - 1000);
    }

    if ($angka < 1000000) {
        return terbilang(floor($angka / 1000)) . ' ribu' . terbilang($angka % 1000);
    }

    if ($angka < 1000000000) {
        return terbilang(floor($angka / 1000000)) . ' juta' . terbilang($angka % 1000000);
    }

    if ($angka < 1000000000000) {
        return terbilang(floor($angka / 1000000000)) . ' miliar' . terbilang(fmod($angka, 1000000000));
    }

    return '';
}

@endphp