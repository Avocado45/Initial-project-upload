<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ $category->category_name }} Products
        </h2>
    </x-slot>

    <div class="space-y-4">
        @forelse ($products as $product)
            <div class="bg-gray-800 rounded-lg p-4 text-gray-100">
                <a href="{{ route('products.show', $product) }}" class="font-semibold hover:underline">
                    {{ $product->product_name }}
                </a>
                <div class="text-sm text-gray-400">
                    £{{ number_format($product->product_price, 2) }}
                </div>
            </div>
        @empty
            <p class="text-gray-300">No products in this category yet.</p>
        @endforelse

        {{ $products->links() }}
    </div>
</x-app-layout>
