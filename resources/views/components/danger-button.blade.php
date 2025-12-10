<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-full bg-red-500 text-white text-sm font-semibold hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-400/60']) }}>
    {{ $slot }}
</button>
