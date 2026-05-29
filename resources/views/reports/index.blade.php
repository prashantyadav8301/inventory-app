<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reports</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Date Filter --}}
            <div class="bg-white shadow rounded-lg p-5">
                <form method="GET" action="{{ route('reports.index') }}">
                    <div class="flex flex-wrap gap-4 items-end">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                            Apply Filter
                        </button>

                        <a href="{{ route('reports.index') }}"
                            class="text-gray-500 border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                            Clear
                        </a>

                    </div>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="text-sm text-gray-500 mb-1">Total Transactions</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $transactions->count() }}</div>
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="text-sm text-gray-500 mb-1">Total Stock In</div>
                    <div class="text-3xl font-bold text-green-600">{{ $totalIn }}</div>
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="text-sm text-gray-500 mb-1">Total Stock Out</div>
                    <div class="text-3xl font-bold text-red-600">{{ $totalOut }}</div>
                </div>

            </div>

            {{-- Export Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('reports.export.transactions', request()->only('from', 'to')) }}"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                    ↓ Export Transactions CSV
                </a>
                <a href="{{ route('reports.export.stock') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    ↓ Export Stock Summary CSV
                </a>
            </div>

            {{-- Transactions Table --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <span class="font-semibold text-sm text-gray-700">
                        Transaction History
                        @if (request('from') || request('to'))
                            <span class="text-xs text-gray-400 ml-2">
                                ({{ request('from') ?? '...' }} → {{ request('to') ?? '...' }})
                            </span>
                        @endif
                    </span>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $index => $transaction)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    {{ $transaction->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $transaction->product->name }}
                                    <span
                                        class="text-xs text-gray-400 font-mono">({{ $transaction->product->sku }})</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($transaction->type === 'in')
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">↑
                                            In</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">↓
                                            Out</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                    {{ $transaction->quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $transaction->note ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                    No transactions found for selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Low Stock Summary --}}
            @if ($lowStockProducts->count() > 0)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                        <span class="text-red-500 font-semibold text-sm">⚠ Current Low Stock Products</span>
                        <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ $lowStockProducts->count() }}
                        </span>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Threshold
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($lowStockProducts as $product)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $product->sku }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product->min_threshold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
