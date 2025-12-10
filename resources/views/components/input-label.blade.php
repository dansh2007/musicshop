@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-gray-200']) }}>
    {{ $value ?? $slot }}
</label>
