<div id='user-status' class="hidden sm:flex sm:items-center sm:ml-6" title='Статус'>
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex items-center text-sm font-medium focus:outline-none transition duration-150 ease-in-out color-theme ">
                <div id='user-status__header' class='user-status-non-ready'>Не готов</div>

                <div class="ml-1 color-light-theme">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            <span value='ready' class='user-status__item text-green-500 block px-4 py-2 text-sm leading-5 color-theme hover:font-bold focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out cursor-pointer'>Готов</span>
            <span value='non-ready' class='user-status__item text-rose-500 block px-4 py-2 text-sm leading-5 color-theme hover:font-bold focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out cursor-pointer'>Не готов</span>
        </x-slot>
    </x-dropdown>
</div>