<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
            <a href="{{ route('products.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                + Add Product
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif --}}

            @if (session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if (session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            {{-- Search & Filter Bar --}}
            <div class="bg-white shadow rounded-lg p-4 mb-4 flex gap-3 items-center">
                <input type="text" id="search" placeholder="Search by name or SKU..."
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <select id="category_filter"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <button id="clear_filters"
                    class="text-sm text-gray-500 border border-gray-300 px-3 py-2 rounded-lg hover:bg-gray-50">
                    Clear
                </button>
            </div>

            {{-- Products Table --}}
            <div class="bg-white shadow rounded-lg overflow-hidden" id="products-table">
                @include('products._table')
            </div>

        </div>
    </div>

    {{-- Ajax Search Script --}}
    <script>
        let searchTimeout = null;

        function fetchProducts() {
            const search = document.getElementById('search').value;
            const category_id = document.getElementById('category_filter').value;

            fetch(`/products?search=${encodeURIComponent(search)}&category_id=${encodeURIComponent(category_id)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('products-table').innerHTML = html;
                });
        }

        // Search input — debounce 400ms
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchProducts, 400);
        });

        // Category filter — immediate
        document.getElementById('category_filter').addEventListener('change', fetchProducts);

        // Clear button
        document.getElementById('clear_filters').addEventListener('click', function() {
            document.getElementById('search').value = '';
            document.getElementById('category_filter').value = '';
            fetchProducts();
        });
    </script>

</x-app-layout>
