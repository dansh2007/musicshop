<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 rounded-full bg-orange-500 text-black font-semibold text-sm hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400/60']) }}>
    {{ $slot }}
</button>
