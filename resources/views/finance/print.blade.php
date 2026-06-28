<!DOCTYPE html>
<html>
<head>
    <button onclick="window.print()">Print</button>

    <title>Surat Pengajuan Pencairan Dana</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            color: #000;
        }

        .kop {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
        }

        .date {
            text-align: right;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .content {
            font-size: 14px;
            line-height: 1.8;
        }

        table {
            font-size: 14px;
        }

        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .ttd {
            text-align: center;
            width: 200px;
            font-size: 14px;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>

{{-- KOP SURAT --}}
<div class="kop">
    <div class="title">SURAT PENGAJUAN PENCAIRAN DANA</div>
</div>

{{-- TANGGAL --}}
<div class="date">
    Tangerang Selatan Banten, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</div>

{{-- TUJUAN SURAT --}}
<div class="content">
    Kepada Yth,<br>
    Bendahara<br>
    PT TRINOVASI DIGITAL SOLUSI<br>
    di Tempat<br><br>

    Dengan hormat,<br><br>

    Yang bertanda tangan di bawah ini:
</div>

{{-- DATA PEMOHON --}}
<div class="content">
    <table>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>__________________________</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>__________________________</td>
        </tr>
        <tr>
            <td>Divisi</td>
            <td>:</td>
            <td>__________________________</td>
        </tr>
    </table>
</div>

{{-- ISI SURAT --}}
<div class="content">
    <br>
    Dengan ini mengajukan permohonan pencairan dana sebesar:<br><br>

    <b style="font-size: 16px;">
        Rp __________________________
    </b><br><br>

    (<i>________________________________________________________</i>)<br><br>

    Adapun dana tersebut akan digunakan untuk:<br><br>

    ________________________________________________<br>
    ________________________________________________<br>
    ________________________________________________<br><br>

    Demikian surat ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.
</div>

{{-- TANDA TANGAN --}}
<div class="signature">

    <div class="ttd">
        Pemohon,<br><br><br><br>
        (_________________)
    </div>

    <div class="ttd">
        Mengetahui,<br><br><br><br>
        (_________________)
    </div>

</div>

<br>


</body>
</html>