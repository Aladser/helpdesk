<button {{ $attributes->merge(['type' => 'submit', 'class' => 'items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
