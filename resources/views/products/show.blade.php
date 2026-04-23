<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            {{ $product->product_name }}
        </h1>
    </x-slot>

    <div class="space-y-6">

        <section class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
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

    <p class="text-lg font-semibold mb-4">
        Price: £{{ number_format($product->product_price, 2) }}
    </p>

    {{-- rest of your section --}}
</section>

@if ($product->retailers->isNotEmpty())
    <section class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-3">Where to buy</h2>

        <ul class="space-y-4">
            @foreach ($product->retailers as $retailer)
                <li class="border-b pb-3" data-retailer-url="{{ $retailer->url }}">
                    <div class="flex items-center gap-3">
                        <a href="{{ $retailer->url }}" target="_blank"
                           class="text-indigo-600 dark:text-indigo-400 hover:underline">
                            {{ $retailer->name }}
                        </a>

                        <button type="button"
                                class="fetch-citation-btn text-xs px-2 py-1 bg-gray-700 text-white rounded hover:bg-gray-600">
                            Fetch citation
                        </button>
                    </div>

                    <div class="citation-result mt-2 text-sm text-gray-600 dark:text-gray-300"></div>
                </li>
            @endforeach
        </ul>
    </section>
@endif

        {{-- Comments section --}}
<section class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-6">
    <h2 class="text-lg font-semibold mb-4">Comments</h2>

    <div id="comments-list">
        @forelse ($product->comments as $comment)
            @include('comments._comment', ['comment' => $comment])
        @empty
            <p data-empty-message class="text-sm text-gray-500 mb-3">
                No comments yet. Be the first!
            </p>
        @endforelse
    </div>

    @auth
        <form id="comment-form"
              action="{{ route('products.comments.store', $product) }}"
              method="POST"
              class="mt-4 space-y-3">
            @csrf
            <textarea name="body"
                      rows="3"
                      class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm"
                      placeholder="Write your comment..."
                      required></textarea>

            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md
                           font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700
                           active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Post Comment
            </button>
        </form>
    @else
        <p class="text-sm text-gray-500 mt-3">
            <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                Log in
            </a>
            to post a comment.
        </p>
    @endauth

    @auth
    @if(auth()->id() === $product->user_id || auth()->user()->isAdmin())
        <div class="flex items-center space-x-3 mt-4">
            <a href="{{ route('products.edit', $product) }}"
               class="text-xs text-indigo-600 hover:underline">
                Edit
            </a>

            <form method="POST" action="{{ route('products.destroy', $product) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-xs text-red-600 hover:underline"
                        onclick="return confirm('Are you sure you want to delete this product?')">
                    Delete
                </button>
            </form>
        </div>
    @endif
@endauth
</section>
    </div>
<script>
document.addEventListener('click', async function (e) {
    const deleteButton = e.target.closest('.comment-delete-btn');
    if (deleteButton) {
        const url = deleteButton.dataset.deleteUrl;
        if (!url) return;

        if (!confirm('Are you sure you want to delete this comment?')) {
            return;
        }

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                console.error('Failed to delete comment', response.status, await response.text());
                return;
            }

            const data = await response.json();
            if (!data.success) {
                console.error('Delete response did not indicate success', data);
                return;
            }

            const commentArticle = deleteButton.closest('article[data-comment-id]');
            if (commentArticle) {
                commentArticle.remove();

                if (commentsList && commentsList.children.length === 0) {
                    const emptyMsg = document.createElement('p');
                    emptyMsg.dataset.emptyMessage = '1';
                    emptyMsg.className = 'text-sm text-gray-500 mb-3';
                    emptyMsg.textContent = 'No comments yet. Be the first!';
                    commentsList.appendChild(emptyMsg);
                }
            }
        } catch (err) {
            console.error('Error sending AJAX delete', err);
        }

        return;
    }

        const citationButton = e.target.closest('.fetch-citation-btn');
    if (citationButton) {
        let url = null;
        let resultBox = null;

        const retailerItem = citationButton.closest('[data-retailer-url]');
        if (retailerItem) {
            url = retailerItem.dataset.retailerUrl;
            resultBox = retailerItem.querySelector('.citation-result');
        }

        if (!url) {
            const commentWrapper = citationButton.closest('[data-comment-citation]');
            if (commentWrapper) {
                const link = commentWrapper.querySelector('a[href]');
                url = link ? link.href : null;
                resultBox = commentWrapper.querySelector('.citation-result');
            }
        }

        if (!url || !resultBox) {
            console.error('No URL or result box found for citation');
            return;
        }

        resultBox.textContent = 'Fetching citation...';

        try {
            const response = await fetch('/api/citations/fetch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ url }),
            });

            const data = await response.json();

            if (response.status === 429) {
                resultBox.textContent = 'Too many citation requests. Please wait a moment.';
                return;
            }

            if (!response.ok || !data.ok) {
                resultBox.textContent = 'Failed to fetch citation.';
                console.error('Citation fetch failed:', data);
                return;
            }

            const citation = data.data;

            resultBox.innerHTML = `
                <div class="rounded border p-3 bg-gray-800 text-gray-100">
                    <p><strong>Title:</strong> ${citation.title ?? 'N/A'}</p>
                    <p><strong>Site:</strong> ${citation.site_name ?? 'N/A'}</p>
                    <p><strong>Description:</strong> ${citation.description ?? 'N/A'}</p>
                </div>
            `;
        } catch (err) {
            resultBox.textContent = 'Error fetching citation.';
            console.error('Citation fetch error:', err);
        }
    }
});
</script>

</x-app-layout>
