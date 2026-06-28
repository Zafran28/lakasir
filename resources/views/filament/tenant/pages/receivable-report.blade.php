<x-filament::page>

    @php

        $year = request('year');

        $query = \App\Models\Tenants\Receivable::query()
            ->where('rest_receivable', '>', 0)
            ->join('sellings', 'sellings.id', '=', 'receivables.selling_id');

        if ($year) {
            $query->where('sellings.code', 'like', '%' . $year);
        }

        $receivables = $query
            ->orderBy('sellings.date', 'asc')
            ->select('receivables.*')
            ->get();

        $total = $receivables->sum('rest_receivable');

    @endphp

    <div class="flex justify-between items-center mb-4 print:hidden">

        <form method="GET" class="flex gap-2">

            <input
                type="text"
                name="year"
                value="{{ request('year') }}"
                placeholder="Search invoice year (2024)"
                class="border rounded-lg px-3 py-2 w-56"
            >

            <button
                type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg"
            >
                Search
            </button>

        </form>

        <button
            type="button"
            onclick="printDiv()"
            class="px-4 py-2 bg-success-600 text-white rounded-lg shadow"
        >
            Print
        </button>

    </div>

    <div id="print-area">

        <div class="space-y-6">

            <div class="bg-white rounded-xl shadow p-6">

                <h2 class="text-2xl font-bold">
                    Receivable Report - Unpaid
                    @if($year)
                        ({{ $year }})
                    @endif
                </h2>

                <p class="text-gray-500 mt-3">
                    Total Remaining Receivables
                </p>

                <div class="text-3xl font-bold text-danger-600 mt-2">
                    IDR {{ number_format($total, 0, ',', '.') }}
                </div>

                <div class="mt-2 text-sm text-gray-500">
                    Total Invoice:
                    {{ $receivables->count() }}
                </div>

            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">

                <div class="overflow-x-auto">

                    <table style="width:100%; border-collapse: collapse; font-size:12px;">

                        <thead>

                            <tr>

                                <th style="border:1px solid #ccc; padding:8px; text-align:left; background:#f3f4f6;">
                                    Invoice
                                </th>

                                <th style="border:1px solid #ccc; padding:8px; text-align:left; background:#f3f4f6;">
                                    Customer
                                </th>

                                <th style="border:1px solid #ccc; padding:8px; text-align:right; background:#f3f4f6;">
                                    Total
                                </th>

                                <th style="border:1px solid #ccc; padding:8px; text-align:right; background:#f3f4f6;">
                                    Remaining
                                </th>

                                <th style="border:1px solid #ccc; padding:8px; text-align:left; background:#f3f4f6;">
                                    Due Date
                                </th>

                                <th style="border:1px solid #ccc; padding:8px; text-align:left; background:#f3f4f6;">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse ($receivables as $item)

                                <tr>

                                    <td style="border:1px solid #ccc; padding:8px;">
                                        {{ $item->selling?->code ?? '-' }}
                                    </td>

                                    <td style="border:1px solid #ccc; padding:8px;">
                                        {{ $item->member?->name ?? '-' }}
                                    </td>

                                    <td style="border:1px solid #ccc; padding:8px; text-align:right;">
                                        IDR {{ number_format($item->total_receivable, 0, ',', '.') }}
                                    </td>

                                    <td style="border:1px solid #ccc; padding:8px; text-align:right; font-weight:bold; color:#dc2626;">
                                        IDR {{ number_format($item->rest_receivable, 0, ',', '.') }}
                                    </td>

                                    <td style="border:1px solid #ccc; padding:8px;">
                                        {{ \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') }}
                                    </td>

                                    <td style="border:1px solid #ccc; padding:8px;">
                                        Unpaid
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        style="border:1px solid #ccc; padding:12px; text-align:center;">

                                        No unpaid receivables found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                        <tfoot>

                            <tr>

                                <td colspan="3"
                                    style="border:1px solid #ccc; padding:8px; text-align:right; font-weight:bold;">

                                    Total Remaining Receivables

                                </td>

                                <td style="border:1px solid #ccc; padding:8px; text-align:right; font-weight:bold; color:#dc2626;">

                                    IDR {{ number_format($total, 0, ',', '.') }}

                                </td>

                                <td colspan="2"
                                    style="border:1px solid #ccc; padding:8px;">
                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script>

        function printDiv() {

            const printContents = document.getElementById('print-area').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

            location.reload();
        }

    </script>

    <style>

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        @media print {

            body {
                background: white !important;
                zoom: 90%;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            tr {
                page-break-inside: avoid;
            }

        }

    </style>

</x-filament::page>