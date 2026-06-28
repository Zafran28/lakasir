<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $selling->code }}</title>

    <style>
        @page {
            size: 51mm 32mm;
            margin: 2mm;
        }

        body {
            font-family: monospace;
            font-size: 13px;
            margin: 0;
            padding: 0;
            width: 51mm;
            color: #000;

            /* 🔥 PRINT SHARP */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            -webkit-font-smoothing: none;
            text-rendering: optimizeSpeed;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: 1000;
            letter-spacing: 0.3px;
        }

        .title {
            font-size: 14px;
            font-weight: 1000;
        }

        table {
            width: 100%;
            font-size: 13px;
            color: #000;
        }

        td {
            padding: 1px 0;
            color: #000;
        }

        .right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 5px;
            font-size: 12px;
            font-weight: 600;
        }

        .label {
            font-weight: 900;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="center bold title">
        BERKAS
    </div>

    <div class="center bold">
        PT. TRINOVASI DIGITAL SOLUSI
    </div>

    <br>

    <!-- INFO -->
    <table>
        <tr>
            <td class="label">Nomor</td>
            <td class="right bold">{{ $selling->code }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal</td>
            <td class="right">
                {{ \Carbon\Carbon::parse($selling->date)->format('d-m-Y') }}
            </td>
        </tr>
    </table>
    <!-- 🔥 AUTO PRINT 1X (ANTI DOUBLE) -->
    <script>
        let printed = false;

        window.addEventListener('load', function () {
            if (!printed) {
                printed = true;

                setTimeout(function () {
                    window.print();
                }, 400);
            }
        });

        window.onafterprint = function () {
            printed = true;
        };
    </script>

</body>
</html>