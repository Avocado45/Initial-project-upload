<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Edit comment
        </h1>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <section class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            {{-- Which product this comment is on --}}
            @if($comment->product)
                <p class="text-sm text-gray-500 mb-3">
                    On product:
                    <a href="{{ route('products.show', $comment->product) }}"
                    class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        {{ $comment->product->product_name }}
                    </a>
                </p>
            @endif

            {{-- Edit form --}}
            <form method="POST" action="{{ route('comments.update', $comment) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Comment
                    </label>
                    <textarea id="body"
                              name="body"
                              rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                     dark:bg-gray-900 dark:text-gray-100 text-sm"
                              required>{{ old('body', $comment->body) }}</textarea>

                    @error('body')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-3">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none
                                   focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save changes
                    </button>

                    @if($comment->product)
                        <a href="{{ route('products.show', $comment->product) }}"
                        class="text-xs text-gray-500 hover:underline">
                            Cancel
                        </a>
                    @else
                        <a href="{{ route('products.index') }}"
                        class="text-xs text-gray-500 hover:underline">
                            Cancel
                        </a>
                    @endif
                    </a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
