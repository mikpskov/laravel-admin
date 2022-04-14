@isset($label)
    <label class="form-label">{{ $label }}</label>
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
