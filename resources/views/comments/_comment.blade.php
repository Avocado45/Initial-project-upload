<article class="border-b border-gray-200 dark:border-gray-700 pb-3 mb-3"
         data-comment-id="{{ $comment->id }}">

    <div class="flex items-center justify-between mb-1">
    @if($comment->user)
        <div class="flex items-center space-x-2">
            <a href="{{ route('users.show', $comment->user) }}"
               class="text-sm font-semibold text-gray-900 dark:text-gray-100 hover:underline">
                {{ $comment->user->name }}
            </a>

            @if($comment->user->role)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold
                             uppercase tracking-wide
                             @if($comment->user->role === 'admin')
                                 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-100
                             @else
                                 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-100
                             @endif">
                    {{ $comment->user->role }}
                </span>
            @endif
        </div>
    @else
        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
            Unknown user
        </span>
    @endif

    <div class="flex items-center space-x-2">
        <span class="text-xs text-gray-500">
            {{ $comment->created_at->diffForHumans() }}
        </span>

        @auth
            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                <div class="flex items-center space-x-2">
                    <a href="{{ route('comments.edit', $comment) }}"
                       class="text-xs text-indigo-500 hover:underline">
                        Edit
                    </a>

                    <button type="button"
                            class="text-xs text-red-500 hover:underline comment-delete-btn"
                            data-delete-url="{{ route('comments.destroy', $comment) }}">
                        Delete
                    </button>
                </div>
            @endif
        @endauth
    </div>
</div>
    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
    {!! preg_replace(
        '/(https?:\/\/[^\s]+)/',
        '<div data-comment-citation class="mt-2">
            <a href="$1" target="_blank" class="text-indigo-500 underline break-all">$1</a>
            <button type="button" class="fetch-citation-btn ml-2 text-xs px-2 py-1 bg-gray-700 text-white rounded hover:bg-gray-600">
                Fetch citation
            </button>
            <div class="citation-result mt-2 text-sm text-gray-200"></div>
        </div>',
        e($comment->body)
    ) !!}
</div>
</article>
