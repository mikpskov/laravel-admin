<article class="shadow-md mb-4 p-4 bg-white flex justify-between" id="notification-{{ $item->id }}">
    <div>
        @switch($item->type)
            @case(\App\Notifications\NewCommentNotification::class)
                @include('notifications.partials.new-comment', ['data' => $item->data])
            @break
        @endswitch
    </div>

    <div>
        @if($item->unread())
            @can('delete', $item)
                <form action="{{ route('users.notifications.destroy', [$item->notifiable_id, $item]) }}" method="POST">
                    @method('delete')
                    @csrf

                    <button title="{{ __('Mark as read') }}">
                        {{ __('Mark as read') }}
                    </button>
                </form>
            @endcan
        @endif
    </div>
</article>
