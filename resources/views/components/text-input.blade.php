@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-rose-300 bg-white text-gray-900 focus:border-rose-500 focus:ring-rose-500 focus:ring-2 rounded-xl shadow-sm']) }}>
