<div {{ $attributes->merge(['class' => 'reactions-block']) }}>
    @guest
        <a href="{{ route('login') }}" class="flex items-center" title="{{ __('Likes') }}">
            <x-icon.bookmark class="mr-2"/>
            <span class="reaction-counter">{{ $model->reactions_save_count }}</span>
        </a>
    @else
        <button
            title="{{ __('Likes') }}"
            data-type="{{ $type }}"
            data-id="{{ $model->getKey() }}"
            @class(['flex items-center reaction-button', 'active' => $model->reacted_save])
        >
            <x-icon.bookmark class="mr-2" filled="{{ $model->reacted_save }}"/>
            <span class="reaction-counter">{{ $model->reactions_save_count }}</span>
        </button>
    @endguest
</div>

@pushOnce('scripts')
<script>
(() => {
    document.querySelectorAll('.reaction-button').forEach(element => {
        element.addEventListener('click', event => {
            addReaction(event.target.closest('button'))
        })
    })
})()

function addReaction(button) {
    const counter = button.querySelector('.reaction-counter')
    const icon = button.querySelector('svg')

    axios({
        method: button.classList.contains('active') ? 'delete' : 'post',
        url: `/reactions/${button.dataset.type}/${button.dataset.id}/save`,
    })
        .then(response => {
            button.classList.toggle("active", response.data.data.reacted);
            counter.innerHTML = response.data.data.reactions_count
            icon.style.fill = response.data.data.reacted ? 'currentColor' : 'none';
        })
        .catch(error => {
            alert(error)
        })
}
</script>
@endpushOnce
