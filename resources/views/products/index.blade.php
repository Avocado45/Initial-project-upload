<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Products
        </h1>
    </x-slot>

    <div class="space-y-6">
        @auth
    <div class="mb-4">
        <a href="{{ route('products.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md
                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700
                  active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Create product
        </a>
    </div>
@endauth
        @forelse ($products as $product)
            <article class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-1">
                    <a href="{{ route('products.show', $product) }}" class="hover:underline">
                        {{ $product->product_name }}
                    </a>
                </h2>

                <p class="text-sm text-gray-500 mb-1">

            <p class="text-sm text-gray-500 mb-1">
            @if($product->user)
                Posted by
                <a href="{{ route('users.show', $product->user) }}"
                class="font-medium text-blue-600 hover:underline">
                    {{ $product->user->name }}
                </a>
                · {{ $product->created_at->diffForHumans() }}
            @else
                <span class="text-gray-500 italic">
                    Posted by unknown user
                </span>
                · {{ $product->created_at->diffForHumans() }}
            @endif
        </p>


<p class="text-sm text-gray-500 mb-2">
    Category: {{ $product->category->category_name ?? 'Uncategorised' }}
    · Year: {{ $product->product_year }}
</p>

                <p class="text-sm text-gray-500 mb-4">
                    Price: £{{ number_format($product->product_price, 2) }}
                </p>
            </article>
        @empty
            <p class="text-gray-700 dark:text-gray-300">No products found.</p>
        @endforelse

        <div>
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
