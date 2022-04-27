<div {{ $attributes->merge(['class' => 'likes-block']) }}>
    @guest
        <a href="{{ route('login') }}" class="flex items-center" title="{{ __('Likes') }}">
            <x-icon.bookmark class="mr-2"/>
            <span class="like-counter">{{ $model->likes_count }}</span>
        </a>
    @else
        <button
            title="{{ __('Likes') }}"
            data-type="{{ $type }}"
            data-id="{{ $model->getKey() }}"
            @class(['flex items-center like-button', 'active' => $model->liked])
        >
            <x-icon.bookmark class="mr-2" filled="{{ $model->liked }}"/>
            <span class="like-counter">{{ $model->likes_count }}</span>
        </button>
    @endguest
</div>

@pushOnce('scripts')
<script>
(() => {
    document.querySelectorAll('.like-button').forEach(element => {
        element.addEventListener('click', event => {
            like(event.target.closest('button'))
        })
    })
})()

function like(likeButton) {
    const resourceType = likeButton.dataset.type
    const resourceId = likeButton.dataset.id
    const likeCounter = likeButton.querySelector('.like-counter')
    const likeIcon = likeButton.querySelector('svg')

    axios({
        method: likeButton.classList.contains('active') ? 'delete' : 'post',
        url: `/likes/${resourceType}/${resourceId}`,
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
@endpushOnce
