@isset($label)
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
@endisset

<select id="{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror">
    @isset($placeholder)
        <option value="" hidden>{{ $placeholder }}</option>
    @endisset

    @isset($empty)
        <option value="">{{ $empty }}</option>
    @endisset

    @foreach ($options as $value => $display)
        <option value="{{ $value }}"{{ $isSelected($value) ? 'selected' : '' }}>{{ $display }}</option>
    @endforeach
</select>

@error($name)
    <span class="invalid-feedback" role="alert">{{ $message }}</span>
@enderror
