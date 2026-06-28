<div class="max-w-full">

  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">Laporan Penjualan</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>

  <p class="mb-4">
    {{ __('Period') }}:
    <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b>
  </p>

  <div class="space-y-6">

    @foreach($reports as $key => $report)

      <table class="w-full table-fixed border-collapse">

        <!-- FIX WIDTH BIAR TIDAK BERANTAKAN -->
        <colgroup>
          <col class="w-80">
          <col class="w-28">
          <col class="w-28">
          <col class="w-24">
          <col class="w-28">
          <col class="w-28">
          <col class="w-28">
        </colgroup>

        <thead>
          <tr>
            <th class="border p-2 text-left" colspan="3">
              Cashier : {{ $report['user'] }}
            </th>
            <th class="border p-2 text-left" colspan="4">
              Nomor : {{ $report['number'] }}
            </th>
          </tr>

          <tr>
            <th class="border p-2 text-left">Items</th>
            <th class="border p-2">Price</th>
            <th class="border p-2">Cost</th>
            <th class="border p-2">Discount</th>
            <th class="border p-2">Total Harga</th>
            <th class="border p-2">Total Cost</th>
            <th class="border p-2">After Discount</th>
          </tr>
        </thead>

        <tbody>

          @foreach($report['transaction']['items'] as $item)
            <tr>
              <td class="border p-2 truncate whitespace-nowrap overflow-hidden">
                {{ $item['product'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $item['product_price'] }} x {{ $item['quantity'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $item['product_cost'] }} x {{ $item['quantity'] }}
              </td>

              <td class="border p-2 text-right">
                ({{ $item['discount_price'] }})
              </td>

              <td class="border p-2 text-right">
                {{ $item['price'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $item['cost'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $item['total_after_discount'] }}
              </td>
            </tr>
          @endforeach

          <!-- SUB TOTAL -->
          <tr class="font-bold">
            <td class="border p-2" colspan="3">Sub Total</td>
            <td class="border p-2 text-right">
              ({{ $report['total']['discount'] }})
            </td>
            <td class="border p-2 text-right">
              {{ $report['total']['gross_selling'] }}
            </td>
            <td class="border p-2 text-right">
              {{ $report['total']['cost'] }}
            </td>
            <td class="border p-2 text-right">
              {{ $report['total']['net_selling'] }}
            </td>
          </tr>

          <!-- DISCOUNT PENJUALAN -->
          <tr class="font-bold">
            <td class="border p-2" colspan="6">
              {{ __('Discount Penjualan') }}
            </td>
            <td class="border p-2 text-right">
              ({{ $report['total']['discount_selling'] }})
            </td>
          </tr>

          <!-- GRAND TOTAL PER TRANSACTION -->
          <tr class="font-bold">
            <td class="border p-2" colspan="6">Total</td>
            <td class="border p-2 text-right">
              {{ $report['total']['grand_total'] }}
            </td>
          </tr>

        </tbody>

      </table>

    @endforeach

  </div>

  <!-- ================= GRAND TOTAL ================= -->
  <table class="w-full table-fixed border-collapse mt-6">

    <colgroup>
      <col class="w-40">
      <col class="w-40">
      <col class="w-40">
      <col class="w-40">
      <col class="w-40">
      <col class="w-40">
      <col class="w-40">
      <col class="w-40">
    </colgroup>

    <thead>
      <tr>
        <th class="border p-2 text-center" colspan="8">
          Grand Total
        </th>
      </tr>

      <tr>
        <th class="border p-2">Cost</th>
        <th class="border p-2">Penjualan</th>
        <th class="border p-2">Discount Penjualan</th>
        <th class="border p-2">Discount Item</th>
        <th class="border p-2">Net Penjualan</th>
        <th class="border p-2">Gross Profit</th>
        <th class="border p-2">Net Profit Before Discount</th>
        <th class="border p-2">Net Profit After Discount</th>
      </tr>
    </thead>

    <tbody>
      <tr class="font-bold">
        <td class="border p-2 text-right">{{ $footer['total_cost'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_gross'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_discount'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_discount_per_item'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_gross_profit'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net_profit_before_discount_selling'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net_profit_after_discount_selling'] }}</td>
      </tr>
    </tbody>

  </table>

</div>