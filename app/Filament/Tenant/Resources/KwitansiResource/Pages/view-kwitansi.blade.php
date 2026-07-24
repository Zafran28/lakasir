<x-filament::page>

    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-2xl font-bold">
                KWITANSI
            </h2>

            <p class="text-gray-500">
                Nomor : {{ $this->record->code }}
            </p>
        </div>

        <div class="flex gap-2">

            {{-- Preview PDF --}}
            <a
                href="{{ route('kwitansi.print', $this->record) }}"
                target="_blank"
                class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 transition"
            >
                👁 Preview PDF
            </a>

            {{-- Download PDF --}}
            <a
                href="{{ route('kwitansi.download', $this->record) }}"
                class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition"
            >
                ⬇ Download PDF
            </a>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-300">

        <table class="w-full border-collapse">

            <thead class="bg-gray-100">

                <tr>
                    <th class="border border-gray-300 p-3 text-left">
                        Nama Barang
                    </th>

                    <th class="border border-gray-300 p-3 text-center">
                        Qty
                    </th>

                    <th class="border border-gray-300 p-3 text-right">
                        Harga
                    </th>

                    <th class="border border-gray-300 p-3 text-right">
                        Subtotal
                    </th>
                </tr>

            </thead>

            <tbody>

                @php
                    $grandTotal = 0;
                @endphp

                @foreach ($this->record->sellingDetails as $item)

                    @php
                        $subtotal = $item->qty * $item->price;
                        $grandTotal += $subtotal;
                    @endphp

                    <tr>

                        <td class="border border-gray-300 p-3">
                            {{ $item->product->name }}
                        </td>

                        <td class="border border-gray-300 p-3 text-center">
                            {{ $item->qty }}
                        </td>

                        <td class="border border-gray-300 p-3 text-right">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>

                        <td class="border border-gray-300 p-3 text-right">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

            <tfoot>

                <tr class="bg-gray-100">

                    <th colspan="3" class="border border-gray-300 p-3 text-right">
                        Grand Total
                    </th>

                    <th class="border border-gray-300 p-3 text-right">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </th>

                </tr>

            </tfoot>

        </table>

    </div>

</x-filament::page>