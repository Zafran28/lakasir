<x-filament::page>

@php
    $transactions = \App\Models\Tenants\FinanceTransaction::latest()->get();

    $totalIncome = $transactions->where('type', 'income')->sum('amount');
    $totalExpense = $transactions->where('type', 'expense')->sum('amount');
    $balance = $totalIncome - $totalExpense;
@endphp

<a href="{{ route('finance.print') }}"
   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow no-print">
    Print
</a>

{{-- SUMMARY --}}
<div class="grid grid-cols-3 gap-4 mt-6">

    <div class="bg-white p-6 rounded-xl shadow">
        <div class="text-gray-500 text-sm">Income</div>
        <div class="text-green-600 text-2xl font-bold">
            IDR {{ number_format($totalIncome, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <div class="text-gray-500 text-sm">Expense</div>
        <div class="text-red-600 text-2xl font-bold">
            IDR {{ number_format($totalExpense, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">
        <div class="text-gray-500 text-sm">Balance</div>
        <div class="text-blue-600 text-2xl font-bold">
            IDR {{ number_format($balance, 0, ',', '.') }}
        </div>
    </div>

</div>

{{-- FORM --}}
<div class="bg-white rounded-xl shadow p-6 mt-6">

    <h2 class="text-lg font-bold mb-4">Add Transaction</h2>

    <form method="POST" action="{{ route('finance.store') }}" class="grid grid-cols-2 gap-4">
        @csrf

        <div>
            <label>Type</label>
            <select name="type" class="w-full border p-2 rounded">
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
        </div>

        <div>
            <label>Category</label>
            <select name="category" class="w-full border p-2 rounded">
                <option value="revenue">Revenue / Penjualan</option>
<option value="receivable_payment">Piutang Usaha Masuk</option>
<option value="other_income">Pendapatan Lain-lain</option>

<option value="purchase">Pembelian Barang</option>
<option value="inventory">Persediaan Barang</option>

<option value="accounts_receivable">Piutang Usaha</option>
<option value="bad_debt">Piutang Tak Tertagih</option>

<option value="accounts_payable">Utang Usaha</option>
<option value="supplier_payment">Pembayaran Supplier</option>

<option value="operational">Biaya Operasional</option>
<option value="salary">Gaji Karyawan</option>
<option value="electricity">Listrik</option>
<option value="internet">Internet</option>
<option value="rent">Sewa</option>
<option value="fuel">BBM</option>
<option value="transportation">Transportasi</option>
<option value="maintenance">Maintenance</option>
<option value="office_supply">ATK & Perlengkapan Kantor</option>

<option value="tax_ppn">PPN</option>
<option value="tax_pph">PPh</option>>
<option value="tax_pph">PPh Pribadi</option>


<option value="orphan_support">Santunan Anak Yatim</option>
<option value="mosque_donation">Pembangunan Mushola</option>



<option value="bank_admin">Biaya Administrasi Bank</option>
<option value="bank_interest">Bunga Bank</option>

<option value="asset_purchase">Pembelian Aset</option>
<option value="equipment">Peralatan</option>
<option value="vehicle">Kendaraan</option>

<option value="other_expense">Pengeluaran Lain-lain</option>
<option value="other_expense">Matrai</option>
            </select>
        </div>

        <div>
            <label>Amount</label>
            <input type="text" name="amount"
                   class="w-full border p-2 rounded"
                   placeholder="62.000.000">
        </div>

        <div>
            <label>Payment Method</label>
            <select name="payment_method" class="w-full border p-2 rounded">
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
                <option value="qris">QRIS</option>
            </select>
        </div>

        <div class="col-span-2">
            <label>Description</label>
            <textarea name="description"
                      class="w-full border p-2 rounded"
                      placeholder="Contoh: Pembayaran invoice INV-012/2024"></textarea>
        </div>

        <div class="col-span-2">
            <button class="bg-black text-white px-4 py-2 rounded">
                Save Transaction
            </button>
        </div>

    </form>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-xl shadow p-6 mt-6">

    <h2 class="text-lg font-bold mb-4">All Transactions</h2>

    <table class="w-full text-sm">
        <thead>
        <tr class="border-b text-left">
            <th>No</th>
            <th>Type</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Method</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        @forelse ($transactions as $t)
            <tr class="border-b">

                <td>{{ $t->transaction_no }}</td>

                <td class="{{ $t->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $t->type }}
                </td>

                <td>{{ $t->category }}</td>

                <td>IDR {{ number_format($t->amount, 0, ',', '.') }}</td>
                <td>{{ $t->description }}</td>

                <td>{{ $t->payment_method }}</td>

                <td>{{ $t->transaction_date->format('d M Y') }}</td>

                <td class="flex gap-2">

                    <button
                        onclick="openEditModal(
                            '{{ $t->id }}',
                            '{{ $t->type }}',
                            '{{ $t->category }}',
                            '{{ number_format($t->amount, 0, ',', '.') }}',
                            '{{ $t->payment_method }}',
                            `{{ $t->description }}`
                        )"
                        class="text-blue-600">
                        Edit
                    </button>

                    <form method="POST" action="{{ route('finance.delete', $t->id) }}">
                        @csrf
                        @method('DELETE')

                        <button onclick="return confirm('Delete?')" class="text-red-600">
                            Delete
                        </button>
                    </form>

                </td>

            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4 text-gray-500">
                    No transactions
                </td>
            </tr>
        @endforelse
        </tbody>

    </table>
</div>

{{-- MODAL EDIT --}}
<div id="editModal" class="hidden fixed inset-0 z-[9999]">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/50"
         onclick="closeEditModal()"></div>

    {{-- Center --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">

        <div class="bg-white w-full max-w-lg rounded-xl shadow-xl">

            {{-- Header --}}
            <div class="border-b px-6 py-3 flex items-center justify-between">

                <h2 class="text-lg font-bold">
                    Edit Transaction
                </h2>

                <button type="button"
                        onclick="closeEditModal()"
                        class="text-gray-500 hover:text-red-500 text-xl">
                    ✕
                </button>

            </div>

            {{-- Form --}}
            <form id="editForm"
                  method="POST"
                  class="px-6 py-3 space-y-3">

                @csrf

                <div>
                    <label class="block mb-1 text-sm font-medium">
                        Type
                    </label>

                    <select id="edit_type"
                            name="type"
                            class="w-full border rounded-lg p-2">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">
                        Category
                    </label>

                    <select id="edit_category"
                            name="category"
                            class="w-full border rounded-lg p-2">

                       <option value="revenue">Revenue / Penjualan</option>
<option value="receivable_payment">Piutang Usaha Masuk</option>
<option value="other_income">Pendapatan Lain-lain</option>

<option value="purchase">Pembelian Barang</option>
<option value="inventory">Persediaan Barang</option>

<option value="accounts_receivable">Piutang Usaha</option>
<option value="bad_debt">Piutang Tak Tertagih</option>

<option value="accounts_payable">Utang Usaha</option>
<option value="supplier_payment">Pembayaran Supplier</option>

<option value="operational">Biaya Operasional</option>
<option value="salary">Gaji Karyawan</option>
<option value="electricity">Listrik</option>
<option value="internet">Internet</option>
<option value="rent">Sewa</option>
<option value="fuel">BBM</option>
<option value="transportation">Transportasi</option>
<option value="maintenance">Maintenance</option>
<option value="office_supply">ATK & Perlengkapan Kantor</option>

<option value="tax_ppn">PPN</option>
<option value="tax_pph">PPh</option>>
<option value="tax_pph">PPh Pribadi</option>


<option value="orphan_support">Santunan Anak Yatim</option>
<option value="mosque_donation">Pembangunan Mushola</option>



<option value="bank_admin">Biaya Administrasi Bank</option>
<option value="bank_interest">Bunga Bank</option>

<option value="asset_purchase">Pembelian Aset</option>
<option value="equipment">Peralatan</option>
<option value="vehicle">Kendaraan</option>

<option value="other_expense">Pengeluaran Lain-lain</option>
<option value="other_expense">Matrai</option>
                        

                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">
                        Amount
                    </label>

                    <input type="number"
                           id="edit_amount"
                           name="amount"
                           class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">
                        Payment Method
                    </label>

                    <select id="edit_method"
                            name="payment_method"
                            class="w-full border rounded-lg p-2">

                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>

                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">
                        Description
                    </label>

                    <textarea id="edit_description"
                              name="description"
                              rows="3"
                              class="w-full border rounded-lg p-2"></textarea>
                </div>

                <div class="flex gap-2 pt-1">

                    <button type="submit"
                            class="flex-1 bg-black text-white py-2 rounded-lg hover:bg-gray-800">
                        Update Transaction
                    </button>

                    <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- SCRIPT --}}
<script>
function openEditModal(
    id,
    type,
    category,
    amount,
    method,
    description
) {
    document.getElementById('editModal')
        .classList.remove('hidden');

    document.getElementById('editForm').action =
        '/finance/' + id + '/update';

    document.getElementById('edit_type').value = type;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_amount').value = amount;
    document.getElementById('edit_method').value = method;
    document.getElementById('edit_description').value =
        description ?? '';
}

function closeEditModal()
{
    document.getElementById('editModal')
        .classList.add('hidden');
}
</script>
<style>
@media print {

    .no-print {
        display: none !important;
    }

    button,
    form,
    .shadow,
    .shadow-xl {
        box-shadow: none !important;
    }

    body {
        background: white !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid #000 !important;
    }

}
</style>
</x-filament::page>