{{ __('New') }} <a href="{{ route('posts.show', $data['post_id']) . "#comment-{$data['comment_id']}" }}" class="font-bold">{{ __('comment') }}</a>
{{ __('by') }} <a href="{{ route('users.show', $data['user_id']) }}" class="font-bold">{{ $data['user_name'] }}</a>
{{ __('on your post') }} <a href="{{ route('posts.show', $data['post_id']) }}" class="font-bold">{{ $data['post_title'] }}</a>.
