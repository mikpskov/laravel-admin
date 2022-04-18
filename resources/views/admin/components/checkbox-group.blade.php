@isset($label)
    <div class="d-flex justify-content-between align-items-center">
        <label class="form-label">{{ $label }}</label>
        <a href="#" id="toggle-button">{{ __('Select all') }}</a>
    </div>
@endisset

<div class="card card-body @error($name) is-invalid border-danger @enderror">
    @foreach($items as $value => $display)
        <div class="form-check">
            <input
                type="checkbox"
                id="{{ $name }}-{{ $value }}"
                name="{{ $name }}[{{ $value }}]"
                class="form-check-input"
                value="1"
                {{ $isChecked($value) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="{{ $name }}-{{ $value }}">{{ $display }}</label>
        </div>
    @endforeach
</div>

@error($name)
    <span class="invalid-feedback" role="alert">{{ $message }}</span>
@enderror

@push('scripts')
<script>
(() => {
    const $toggleButton = document.getElementById('toggle-button')
    const checkboxes = document.querySelectorAll(".form-check-input")

    $toggleButton.addEventListener('click', event => {
        const $button = event.target
        const isSelected = $button.dataset.selected !== undefined

        event.preventDefault()
        checkboxes.forEach($checkbox => $checkbox.checked = !isSelected)

        isSelected
            ? delete $button.dataset.selected
            : $button.dataset.selected = 'selected'
    })

    checkboxes.forEach($checkbox => $checkbox.addEventListener('change', () => {
        delete $toggleButton.dataset.selected
    }))
})()
</script>
@endpush
