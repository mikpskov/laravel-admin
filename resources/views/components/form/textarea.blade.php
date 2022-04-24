@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'rounded-md border-2 border-gray focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full']) }}></textarea>
