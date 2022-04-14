@isset($label)
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
@endisset

<input
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    class="form-control @error($name) is-invalid @enderror"
    value="{{ $value }}"
>

@error($name)
    <span class="invalid-feedback" role="alert">{{ $message }}</span>
@enderror
