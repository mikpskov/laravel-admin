@isset($label)
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
@endisset

<textarea
    id="{{ $name }}"
    name="{{ $name }}"
    class="form-control @error($name) is-invalid @enderror"
    placeholder="{{ $placeholder }}"
    {{ $attributes }}
>{{ $value }}</textarea>

@error($name)
    <span class="invalid-feedback" role="alert">{{ $message }}</span>
@enderror
