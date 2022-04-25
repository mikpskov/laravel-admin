@guest
    <a href="{{ route('login') }}" class="flex items-center" title="{{ __('Likes') }}" {{ $attributes }}>
        <x-icon.heart class="mr-2"/>
        <span>55</span>
    </a>
@else
    <button
        title="{{ __('Likes') }}"
        data-type="{{ $type }}"
        data-id="{{ $id }}"
        {{ $attributes->merge(['class' => 'flex items-center like-button' . ($active ? ' active' : '')]) }}
    >
        <x-icon.heart class="mr-2" filled="{{ $active }}"/>
        <span class="like-counter">{{ $count }}</span>
    </button>
@endguest

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
