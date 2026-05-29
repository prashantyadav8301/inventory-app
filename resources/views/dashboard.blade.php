<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="text-sm text-gray-500 mb-1">Total Products</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $totalProducts }}</div>
                    <a href="{{ route('products.index') }}"
                        class="text-xs text-indigo-400 hover:underline mt-1 inline-block">View all →</a>
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="text-sm text-gray-500 mb-1">Categories</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $totalCategories }}</div>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('categories.index') }}"
                            class="text-xs text-indigo-400 hover:underline mt-1 inline-block">View all →</a>
                    @endif
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="text-sm text-gray-500 mb-1">Suppliers</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $totalSuppliers }}</div>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('suppliers.index') }}"
                            class="text-xs text-indigo-400 hover:underline mt-1 inline-block">View all →</a>
                    @endif
                </div>

                <div class="bg-white shadow rounded-lg p-5 {{ $lowStockCount > 0 ? 'border-l-4 border-red-500' : '' }}">
                    <div class="text-sm text-gray-500 mb-1">Low Stock Alerts</div>
                    <div class="text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $lowStockCount }}
                    </div>
                    <span
                        class="text-xs {{ $lowStockCount > 0 ? 'text-red-400' : 'text-green-400' }} mt-1 inline-block">
                        {{ $lowStockCount > 0 ? 'Needs attention' : 'All good ✓' }}
                    </span>
                </div>

            </div>

            {{-- Low Stock Table --}}
            @if ($lowStockCount > 0)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                        <span class="text-red-500 font-semibold text-sm">⚠ Low Stock Products</span>
                        <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ $lowStockCount }}
                        </span>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current
                                    Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min
                                    Threshold</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
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
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('stock-transactions.create') }}?product_id={{ $product->id }}"
                                            class="text-indigo-600 hover:underline text-xs">
                                            + Add Stock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Recent Transactions --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <span class="font-semibold text-sm text-gray-700">Recent Transactions</span>
                    <a href="{{ route('stock-transactions.index') }}"
                        class="text-xs text-indigo-500 hover:underline">View all →</a>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $transaction->product->name }}
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
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $transaction->user->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    {{ $transaction->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                    No transactions yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-app-layout>
