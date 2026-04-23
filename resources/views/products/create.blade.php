<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Create product
        </h1>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <section class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('products.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Name
                    </label>
                    <input id="product_name" name="product_name" type="text"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                  dark:bg-gray-900 dark:text-gray-100 text-sm"
                           value="{{ old('product_name') }}" required>
                    @error('product_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="product_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Year
                        </label>
                        <input id="product_year" name="product_year" type="number"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                      dark:bg-gray-900 dark:text-gray-100 text-sm"
                               value="{{ old('product_year') }}" required>
                        @error('product_year')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="product_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Price (£)
                        </label>
                        <input id="product_price" name="product_price" type="number" step="0.01"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                      dark:bg-gray-900 dark:text-gray-100 text-sm"
                               value="{{ old('product_price') }}" required>
                        @error('product_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Category
                    </label>
                    <select id="category_id" name="category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                   dark:bg-gray-900 dark:text-gray-100 text-sm">
                        <option value="">Uncategorised</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-3">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none
                                   focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create
                    </button>

                    <a href="{{ route('products.index') }}"
                       class="text-xs text-gray-500 hover:underline">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
