<header class="bg-dark-theme color-light-theme shadow-lg shadow-blue-500/40">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between">
        <h2 class="font-semibold text-xl leading-tight">{{ $slot }}</h2>
        @if (Auth::user()->role->name == 'executor')
        <x-user-status></x-user-status>
        @endif
    </div>
</header>
