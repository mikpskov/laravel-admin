<div
    data-type="{{ $type }}"
    data-id="{{ $model->getKey() }}"
    {{ $attributes->merge(['class' => 'flex items-center votes-block']) }}
>
    @can('vote', $model)
        <button title="{{ __('Vote up') }}" @class(['vote-button mr-2', 'active' => $model->voted_up]) data-direction="1">
            <x-icon.thumb-up :filled="$model->voted_up"/>
        </button>
    @endcan

    <span
        class="votes-total-counter {{ $total >= 0 ? 'text-green-600' : 'text-red-600' }}"
        title="{{ __('Total votes :count: ↑:up and ↓:down', [
            'count' => $count,
            'up' => $model->votes_up_count,
            'down' => $model->votes_down_count,
        ]) }}"
    >
        @if($total >= 0)+@endif{{ $total }}
    </span>

    @can('vote', $model)
        <button title="{{ __('Vote down') }}" @class(['vote-button ml-2', 'active' => $model->voted_down]) data-direction="0">
            <x-icon.thumb-down :filled="$model->voted_down"/>
        </button>
    @endcan
</div>

@pushOnce('scripts')
<script>
(() => {
    document.querySelectorAll('.vote-button').forEach(element => {
        element.addEventListener('click', event => {
            vote(event.target.closest('.vote-button'))
        })
    })
})()

function vote(button) {
    const block = button.closest('.votes-block')

    const success = response => {
        // change total counter
        const counter = block.querySelector('.votes-total-counter')

        counter.innerHTML = (response.data.data.total >= 0 ? '+' : '') + response.data.data.total
        response.data.data.total >= 0
            ? counter.classList.replace('text-red-600', 'text-green-600')
            : counter.classList.replace('text-green-600', 'text-red-600')

        // toggle buttons
        const upButton = block.querySelector('.vote-button[data-direction="1"]')
        const downButton = block.querySelector('.vote-button[data-direction="0"]')

        if (response.data.data.voted) {
            upButton.classList.toggle("active", response.data.data.direction)
            downButton.classList.toggle("active", !response.data.data.direction)

            upButton.querySelector('svg').style.fill = response.data.data.direction ? 'currentColor' : 'none'
            downButton.querySelector('svg').style.fill = response.data.data.direction ? 'none' : 'currentColor'
        } else {
            if (response.data.data.direction) {
                upButton.classList.toggle("active", false)
                upButton.querySelector('svg').style.fill = 'none'
            } else {
                downButton.classList.toggle("active", false)
                downButton.querySelector('svg').style.fill = 'none'
            }
        }
    }

    axios({
        method: button.classList.contains('active') ? 'delete' : 'post',
        url: `/votes/${block.dataset.type}/${block.dataset.id}`,
        data: {
            direction: button.dataset.direction > 0,
        }
    })
        .then(success)
        .catch(error => {
            alert(error)
        })
}
</script>
@endpushOnce
