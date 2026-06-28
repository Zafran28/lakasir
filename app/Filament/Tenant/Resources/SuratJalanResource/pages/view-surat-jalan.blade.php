<x-filament::page>

    <h2 style="font-size:20px; font-weight:bold;">
        SURAT JALAN
    </h2>

    <table width="100%" border="1" cellspacing="0" cellpadding="8">
        <tr>
            <th>Nama Barang</th>
            <th>Qty</th>
        </tr>

        @foreach($this->getRecord()->sellingDetails as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}</td>
            </tr>
        @endforeach
    </table>

</x-filament::page>