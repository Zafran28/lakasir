<x-filament::page>

    <h2 style="font-size:20px; font-weight:bold;">
        KWITANSI
    </h2>

    <p>
        Nomor: {{ $this->record->code }}
    </p>

    <table width="100%" border="1" cellspacing="0" cellpadding="8">
        <tr>
            <th>Nama Barang</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>

        @foreach($this->record->sellingDetails as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->price) }}</td>
            </tr>
        @endforeach
    </table>

</x-filament::page>