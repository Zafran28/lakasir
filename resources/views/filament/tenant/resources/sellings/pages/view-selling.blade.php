@php
    use App\Features\{SellingTax};
    use App\Models\Tenants\{Profile, Setting, About};

    function rupiah($angka)
    {
        return 'Rp. ' . number_format($angka, 0, ',', '.');
    }
@endphp

<x-filament-panels::page>

<style>

/* VIEW NORMAL */
.invoice-footer {
    position: static;
    margin-top: 40px;
}

.print-only {
    display: none;
}

/* PRINT */
@media print {

    .invoice-footer {
        position: absolute;
        bottom: 20mm; /* 🔥 ini = ±2 cm dari bawah */
        left: 0;
        right: 0;
    }

    .company-footer {
        margin-top: 5mm;
    }

    .invoice-body {
        padding-bottom: 100mm;
    }
}
</style>

<x-filament::section
    id="printElement"
    class="text-[13px] leading-tight"
>

{{-- ================= BODY ================= --}}
<div class="invoice-body">

    {{-- HEADER --}}
    <div class="invoice-header flex justify-between items-end">

        <div class="w-40">
            <img src="{{ asset('storage/product/Trinovasi.png') }}"
                 style="width:300px; height:auto; display:block;">
        </div>

        <div class="text-right">
            <h1 class="text-3xl font-bold tracking-widest">INVOICE</h1>
            <div class="border-b border-gray-400 w-40 ml-auto mt-1"></div>
        </div>

    </div>

    {{-- INFO --}}
    <div class="grid grid-cols-2 border border-gray-300 mb-2">

        <div class="p-2 border-r">
            <div class="font-semibold uppercase">
                To {{ $record->member?->name ?? 'N/A' }}
            </div>
            <div class="text-gray-700">
                {{ $record->member?->address ?? 'N/A' }}
            </div>
        </div>

        <div class="p-2">

           <tr>
 <table class="w-full text-[13px]">

    {{-- NOMOR --}}
    <tr>
        <td class="font-semibold w-24">Nomor</td>
        <td class="w-3">:</td>
        <td class="text-right">

            <span class="hidden print:inline">
                {{ $record->code }}
            </span>

            <form method="POST"
                  action="{{ route('selling.update.code', $record->id) }}"
                  class="print:hidden flex justify-end items-center gap-2">
                @csrf

                <input type="text"
                       name="code"
                       value="{{ $record->code }}"
                       class="border rounded px-2 py-1 w-52">

                <button type="submit"
                        class="bg-blue-500 text-white px-3 py-1 rounded">
                    Simpan
                </button>
            </form>

        </td>
    </tr>

    {{-- TANGGAL --}}
    <tr>
        <td class="font-semibold">Tanggal</td>
        <td>:</td>
        <td class="text-right">

            <span class="hidden print:inline">
                {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
            </span>

            <form method="POST"
                  action="{{ route('selling.update.date', $record->id) }}"
                  class="print:hidden flex justify-end items-center gap-2">
                @csrf

                <input type="date"
                       name="date"
                       value="{{ \Carbon\Carbon::parse($record->date)->format('Y-m-d') }}"
                       class="border rounded px-2 py-1">

                <button type="submit"
                        class="bg-green-500 text-white px-3 py-1 rounded">
                    Simpan
                </button>
            </form>

        </td>
    </tr>

</table>

        </div>

    </div>

    {{-- TABLE ITEM --}}
    <table class="w-full border text-[13px]">

        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2 text-left">URAIAN</th>
                <th class="border p-2">QTY</th>
                <th class="border p-2 text-right">HARGA SATUAN</th>
                <th class="border p-2 text-right">JUMLAH</th>
            </tr>
        </thead>

        <tbody>
            @foreach($record->sellingDetails as $detail)
            <tr>
                <td class="border p-2">{{ $detail->product->name }}</td>
                <td class="border p-2 text-center">
                    {{ number_format($detail->qty, 0, ',', '.') }} {{ $detail->product->unit }}
                </td>
                <td class="border p-2 text-right">{{ rupiah($detail->price_per_unit) }}</td>
                <td class="border p-2 text-right">{{ rupiah($detail->total_price) }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td class="border p-2 font-semibold">TOTAL</td>
                <td class="border p-2 text-right">{{ rupiah($record->grand_total_price) }}</td>
            </tr>

            <tr>
                <td colspan="2"></td>
                <td class="border p-2 font-semibold">PPN 11%</td>
                <td class="border p-2 text-right">{{ rupiah($record->tax_price) }}</td>
            </tr>

            <tr>
                <td colspan="2"></td>
                <td class="border p-2 font-semibold">JUMLAH</td>
                <td class="border p-2 text-right font-bold">{{ rupiah($record->total_price) }}</td>
            </tr>
        </tfoot>

    </table>

    {{-- TERBILANG --}}
    @php
    function terbilang($angka){
        $angka = abs($angka);
        $huruf = ['','satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan','sepuluh','sebelas'];

        if ($angka < 12) return $huruf[$angka];
        if ($angka < 20) return terbilang($angka-10).' belas';
        if ($angka < 100) return terbilang($angka/10).' puluh '.terbilang($angka%10);
        if ($angka < 200) return 'seratus '.terbilang($angka-100);
        if ($angka < 1000) return terbilang($angka/100).' ratus '.terbilang($angka%100);
        if ($angka < 2000) return 'seribu '.terbilang($angka-1000);
        if ($angka < 1000000) return terbilang($angka/1000).' ribu '.terbilang($angka%1000);
        if ($angka < 1000000000) return terbilang($angka/1000000).' juta '.terbilang($angka%1000000);

        return '';
    }
    @endphp

    <p class="mt-3 font-semibold">Terbilang :</p>

    <div class="border p-2 mt-1 uppercase font-bold bg-gray-50">
        {{ ucfirst(trim(terbilang((int) $record->total_price))) }} rupiah
    </div>

    <p class="text-xs text-gray-600 mt-2">
        Pembayaran dengan cheq/bilyet giro
    </p>

    <p class="text-xs text-gray-600">
       Baru dianggap lunas jika telah diuangkan, kurs dolar berlaku pada saat pembayaran
    </p>

</div>

{{-- ================= FOOTER FIX WORD STYLE ================= --}}
<div class="invoice-footer">

    <div class="flex justify-between text-[12px]">

        <div class="flex justify-center">
    <div class="border p-3 text-gray-700 text-center w-64">
        <br></br>
        Transfer ke Bank BJB KCP Pahlawan Seribu<br>
        No Rekening 0157614932100<br>
        a.n Trinovasi Digital Solusi PT

    </div>
</div>

            <div class="text-right w-48">
            <p class="mb-24">TRINOVASI DIGITAL SOLUSI, PT</p>

            <p>( GILANG SETIA DARMAWAN )</p>
            <div class="border-t mt-2"></div>
            <p class="text-xs text-gray-500">Direktur</p>
        </div>
    </div>

  <div class="company-footer text-center text-xs leading-tight border-t pt-2 mt-6">

    <p class="font-bold uppercase">
        PT TRINOVASI DIGITAL SOLUSI
    </p>

    <p>
        Jl. Raya Pasar Jengkol No. 39 (BSD – Parung),
        Babakan Setu, Tangerang Selatan Banten
    </p>

    <p class="mt-1">
        Telp: 021-75874606 |
        0852-8608-3882 |
        Email: digitalsolusitrinovasi@gmail.com
    </p>

</div>

</div>

</x-filament::section>

</x-filament-panels::page>
@script()
<script>
  console.log(@js($record));
  document.getElementById('printInvoice').addEventListener('click', () => {
    const printContents = document.getElementById("printElement").innerHTML;
    const originalContents = document.body.innerHTML;


    document.body.innerHTML = printContents;

    window.print();

    window.location.reload();
  });
  document.getElementById('printButton').addEventListener('click', async () => {
    let selling = @js($record);
    let about = @js($about);
    const printerData = getPrinter();

    try {
      if (!printerData) {
        new FilamentNotification()
          .title('@lang('You should choose the printer first in printer setting')')
          .danger()
          .actions([
            new FilamentNotificationAction('Setting')
              .icon('heroicon-o-cog-6-tooth')
              .button()
              .url('/member/printer'),
          ])
          .send()
      } else {
        const printer = new Printer(printerData.printerId);
        let printerAction = printer.font('a');
        if(about != undefined || about != null) {
          printerAction.size(1)
            .align('center')
            .text(about.shop_name)
            .size(0)
            .text(about.shop_location);
          if(printerData.header != undefined) {
            printerAction
              .text(printerData.header);
          }
          printerAction.align('left')
            .text('-------------------------------');
        }
        printerAction.table(['@lang('Cashier')', selling.user.name])
        if(selling.table != undefined && selling.table != null) {
          printerAction.table(['@lang('Table')', selling.table.number])
        }
        printerAction.table(['@lang('Payment method')', selling.payment_method.name]);
        if(selling.member != undefined && selling.member != null) {
          printerAction
            .table(['Member', selling.member.name]);
        }
        printerAction
          .text('-------------------------------');
        selling.selling_details.forEach(sellingDetail => {
          let price = sellingDetail.price;
          let text = moneyFormat(sellingDetail.price / sellingDetail.qty) + ' x ' + sellingDetail.qty.toString();
          printerAction.table([sellingDetail.product.name, moneyFormat(sellingDetail.price / sellingDetail.qty) + ' x ' + sellingDetail.qty.toString()])
          if (sellingDetail.discount_price > 0) {
            price = price - sellingDetail.discount_price;
            printerAction
              .align('right')
              .text(`(${moneyFormat(sellingDetail.discount_price)})`)
          }
          printerAction
            .align('right')
            .text(moneyFormat(price))
            .align('left')
        });
        printerAction
          .text('-------------------------------');
        if("@js(feature(SellingTax::class))" == 'true') {
          printerAction.table(['@lang('Tax')', `${selling.tax}%`])
            .table(['@lang('Tax price')', moneyFormat(selling.tax_price)]);
        }
        printerAction
          .table(['@lang('Subtotal')', moneyFormat(selling.total_price)])
          .table(['@lang('Discount')', `(${moneyFormat(selling.total_discount_per_item + selling.discount_price)})`])
          .table(['@lang('Total price')', moneyFormat(selling.grand_total_price)])
          .text('-------------------------------')
          .table(['@lang('Payed money')', moneyFormat(selling.payed_money)])
          .table(['@lang('Change')', moneyFormat(selling.money_changes)])
          .align('center');
        if(printerData.footer != undefined) {
          printerAction
            .text(printerData.footer);
        }
        printerAction.align('left')
          .text('copy');

        await printerAction
          .cut()
          .print();
      }
    } catch (error) {
      console.error(error);
    }
  });
</script>
@endscript
