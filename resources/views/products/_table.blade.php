<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($products as $product)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $product->sku }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $product->category->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $product->supplier->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">₹{{ number_format($product->price, 2) }}</td>
                <td class="px-6 py-4 text-sm">
                    <span
                        class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $product->isLowStock() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                        {{ $product->quantity }}
                        {{ $product->isLowStock() ? '⚠ Low' : '' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm space-x-2">
                    <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:underline">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                        onsubmit="return confirm('Delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-gray-400">No products found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="px-6 py-4">
    {{ $products->links() }}
</div>
