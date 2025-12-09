@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-rose-700']) }}>
    {{ $value ?? $slot }}
</label>
