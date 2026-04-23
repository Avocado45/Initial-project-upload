<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ $user->name }}'s activity
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Role:
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                             bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                    {{ $user->role ? ucfirst($user->role) : 'User' }}
                </span>
            </p>
        </div>
    </x-slot>

    <div class="space-y-8 max-w-4xl mx-auto">

        {{-- Items / Products created by this user --}}
        <section>
            <h2 class="text-xl font-semibold mb-3 text-gray-900 dark:text-gray-100">
                Items
            </h2>

            @if($products->isEmpty())
                <p class="text-sm text-gray-500">
                    This user hasn’t posted any items yet.
                </p>
            @else
                <ul class="space-y-3">
                    @foreach($products as $product)
                        <li class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('products.show', $product) }}"
                                   class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $product->product_name }}
                                </a>
                                <span class="text-xs text-gray-500">
                                    {{ $product->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-500 mt-1">
                                Category: {{ $product->category->category_name ?? 'Uncategorised' }}
                                · Year: {{ $product->product_year }}
                                · {{ $product->comments_count }} comment{{ $product->comments_count === 1 ? '' : 's' }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Comments created by this user --}}
        <section>
            <h2 class="text-xl font-semibold mb-3 text-gray-900 dark:text-gray-100">
                Comments
            </h2>

            @if($comments->isEmpty())
                <p class="text-sm text-gray-500">
                    This user hasn’t posted any comments yet.
                </p>
            @else
                <ul class="space-y-3">
                    @foreach($comments as $comment)
                        <li class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-1">
                                <div class="text-xs text-gray-500">
                                    On
                                    @if($comment->product)
                                    <a href="{{ route('products.show', $comment->product) }}"
                                    class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $comment->product->product_name }}
                                    </a>
                                @else
                                    <span class="text-gray-500 italic">[Product deleted]</span>
                                @endif
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $comment->body }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
