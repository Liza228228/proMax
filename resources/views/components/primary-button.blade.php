<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:from-rose-600 hover:to-pink-600 focus:from-rose-600 focus:to-pink-600 active:from-rose-700 active:to-pink-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 shadow-lg']) }}>
    {{ $slot }}
</button>
