<div class="max-w-full">

  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">{{ __('Purchasing Report') }}</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>

  <p class="mb-4">
    {{ __('Period') }}:
    <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b>
  </p>

  <div class="space-y-6">

    @foreach($reports as $report)

      <table class="w-full table-fixed border-collapse">

        <!-- FIX COLUMN WIDTH -->
        <colgroup>
          <col class="w-64">
          <col class="w-24">
          <col class="w-24">
          <col class="w-32">
          <col class="w-32">
          <col class="w-32">
          <col class="w-32">
        </colgroup>

        <!-- SUPPLIER + DATE -->
        <thead>
          <tr>
            <th class="border p-2 text-left" colspan="6">
              {{ __('Supplier') }}: {{ $report['supplier'] }}
            </th>
          </tr>

          <tr>
            <th class="border p-2 text-left" colspan="6">
              {{ __('Date') }}: {{ $report['date'] }}
            </th>
          </tr>

          <tr>
            <th class="border p-2 text-left">Product Name</th>
            <th class="border p-2">Unit</th>
            <th class="border p-2">Stock</th>
            <th class="border p-2">Cost/Stock</th>
            <th class="border p-2">Total Cost</th>
            <th class="border p-2">Price/Stock</th>
            <th class="border p-2">Total Price</th>
          </tr>
        </thead>

        <tbody>

          @foreach($report['stocks'] as $stock)
            <tr>
              <td class="border p-2 truncate whitespace-nowrap overflow-hidden">
                {{ $stock['product_name'] }}
              </td>

              <td class="border p-2 text-center">
                {{ $stock['product_unit'] }}
              </td>

              <td class="border p-2 text-center">
                {{ $stock['init_stock'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $stock['initial_price'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $stock['total_initial_price'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $stock['selling_price'] }}
              </td>

              <td class="border p-2 text-right">
                {{ $stock['total_selling_price'] }}
              </td>
            </tr>
          @endforeach

          <!-- SUBTOTAL -->
          <tr class="font-bold">
            <td class="border p-2" colspan="4">
              {{ __('Subtotal') }}
            </td>

            <td class="border p-2 text-right">
              {{ $report['subtotal_total_initial_price'] }}
            </td>

            <td class="border p-2"></td>

            <td class="border p-2 text-right">
              {{ $report['subtotal_total_selling_price'] }}
            </td>
          </tr>

        </tbody>

      </table>

    @endforeach

  </div>

  <!-- ================= GRAND TOTAL ================= -->
  <table class="w-full table-fixed border-collapse mt-6">

    <colgroup>
      <col class="w-64">
      <col class="w-64">
    </colgroup>

    <thead>
      <tr>
        <th class="border p-2 text-center" colspan="2">
          Grand Total
        </th>
      </tr>
    </thead>

    <tbody>

      <tr>
        <td class="border p-2">Cost</td>
        <td class="border p-2 text-right">
          <b>{{ $footer['grand_total_initial_price'] }}</b>
        </td>
      </tr>

      <tr>
        <td class="border p-2">Selling Price</td>
        <td class="border p-2 text-right">
          <b>{{ $footer['grand_total_selling_price'] }}</b>
        </td>
      </tr>

    </tbody>

  </table>

</div>