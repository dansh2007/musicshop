<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 rounded-full border border-white/20 bg-transparent text-sm text-gray-100 hover:border-white/50 hover:bg-white/5 disabled:opacity-40 transition']) }}>
    {{ $slot }}
</button>
