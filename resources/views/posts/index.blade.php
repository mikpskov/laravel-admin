@extends('layouts.app')

@section('content')
    @foreach($items as $item)
        @include('posts.partials.post', ['isList' => true])
    @endforeach

    <div class="flex justify-center">
        {{--
        <a href="#" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Prev') }}
        </a>
        --}}

        {{ $items->links() }}

        {{--
        <a href="#" class="flex items-center">
            {{ __('Next') }}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        --}}
    </div>
@endsection

@push('scripts')
<script>
(() => {
    document.querySelectorAll('.like-button').forEach(element => {
        element.addEventListener('click', event => {
            like(event.target.closest('article'))
        })
    })
})()

function like(articleBlock) {
    const likeButton = articleBlock.querySelector('.like-button')
    const likeCounter = articleBlock.querySelector('.like-counter');
    const likeIcon = likeButton.querySelector('svg')
    const resourceId = articleBlock.dataset.postId

    axios({
        method: likeButton.classList.contains('active') ? 'delete' : 'post',
        url: `/likes/posts/${resourceId}`,
    })
        .then(response => {
            likeButton.classList.toggle("active", response.data.data.liked);
            likeCounter.innerHTML = response.data.data.likesCount
            likeIcon.style.fill = response.data.data.liked ? 'currentColor' : 'none';
        })
        .catch(error => {
            alert(error)
        })
}
</script>
@endpush
