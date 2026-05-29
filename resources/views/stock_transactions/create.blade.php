<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Stock Transaction</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('stock-transactions.store') }}" method="POST">
                    @csrf

                    {{-- Product Select --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                        <select name="product_id" id="product_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                                @error('product_id') border-red-500 @enderror">
                            <option value="">-- Select Product --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-stock="{{ $product->quantity }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (SKU: {{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Current Stock Badge --}}
                    <div id="stock_info" class="mb-4 hidden">
                        <span class="text-sm text-gray-600">Current Stock:
                            <span id="current_stock" class="font-semibold text-indigo-600"></span>
                        </span>
                    </div>

                    {{-- Type --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Type *</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="in"
                                    {{ old('type', 'in') === 'in' ? 'checked' : '' }} class="text-indigo-600">
                                <span class="text-sm text-green-700 font-medium">Stock In ↑</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="out"
                                    {{ old('type') === 'out' ? 'checked' : '' }} class="text-indigo-600">
                                <span class="text-sm text-red-700 font-medium">Stock Out ↓</span>
                            </label>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                               @error('quantity') border-red-500 @enderror">
                        @error('quantity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Note --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                        <textarea name="note" rows="2" placeholder="e.g. Received from supplier, Sold to customer..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('note') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
                            Submit Transaction
                        </button>
                        <a href="{{ route('stock-transactions.index') }}"
                            class="text-gray-600 px-5 py-2 rounded-lg text-sm border hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Current stock Ajax show --}}
    <script>
        document.getElementById('product_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const stock = selected.getAttribute('data-stock');
            const infoDiv = document.getElementById('stock_info');

            if (this.value) {
                document.getElementById('current_stock').textContent = stock;
                infoDiv.classList.remove('hidden');
            } else {
                infoDiv.classList.add('hidden');
            }
        });
    </script>

</x-app-layout>
