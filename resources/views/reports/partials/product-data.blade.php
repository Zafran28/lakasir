<div class="max-w-full">

  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">{{ __('Product Report') }}</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>

  <p class="mb-4">
    {{ __('Period') }}:
    <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b>
  </p>

  <!-- ================= PRODUCT TABLE ================= -->
  <table class="w-full table-fixed border-collapse">

    <colgroup>
      <col class="w-12">
      <col class="w-80">
      <col class="w-24">
      <col class="w-16">
      <col class="w-28">
      <col class="w-28">
      <col class="w-28">
      <col class="w-28">
    </colgroup>

    <thead>
      <tr>
        <th class="border p-2">No</th>
        <th class="border p-2 text-left">Product Name</th>
        <th class="border p-2">Price</th>
        <th class="border p-2">Qty</th>
        <th class="border p-2">Selling</th>
        <th class="border p-2">Discount</th>
        <th class="border p-2">Net Selling</th>
        <th class="border p-2">Gross Profit</th>
        <th class="border p-2">Net Profit</th>
      </tr>
    </thead>

    <tbody>
      @foreach($reports as $key => $report)
        <tr>
          <td class="border p-2 text-center">{{ $key + 1 }}</td>

          <td class="border p-2 truncate whitespace-nowrap overflow-hidden">
            {{ $report['name'] }}
          </td>

          <td class="border p-2 text-right">{{ $report['selling_price'] }}</td>
          <td class="border p-2 text-center">{{ $report['qty'] }}</td>
          <td class="border p-2 text-right">{{ $report['selling'] }}</td>
          <td class="border p-2 text-right">{{ $report['discount_price'] }}</td>
          <td class="border p-2 text-right">{{ $report['total_after_discount'] }}</td>
          <td class="border p-2 text-right">{{ $report['gross_profit'] }}</td>
          <td class="border p-2 text-right">{{ $report['net_profit'] }}</td>
        </tr>
      @endforeach

      <!-- TOTAL -->
      <tr class="font-bold">
        <td class="border p-2 text-center" colspan="3">Total</td>
        <td class="border p-2 text-center">{{ $footer['total_qty'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_gross'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_all_discount_per_item'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_gross_profit'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net_profit_before_discount_selling'] }}</td>
      </tr>
    </tbody>
  </table>


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
        <td class="border p-2 text-right">{{ $footer['total_all_discount_per_item'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_gross_profit'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net_profit_before_discount_selling'] }}</td>
        <td class="border p-2 text-right">{{ $footer['total_net_profit_after_discount_selling'] }}</td>
      </tr>
    </tbody>

  </table>

</div>