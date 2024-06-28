<x-app-layout>
    @section('title')
    <x-title>статистика</x-title>
    @endsection

    @section('header')
    <x-header>Статистика</x-header>
    @endsection

    @section('meta')
    <x-meta name='role'>{{ Auth::user()->role->name }}</x-meta>
    <x-meta name='websocket'>{{$websocket_addr}}</x-meta>
    @endsection
    
    @section('css')
    <x-css-link>css/stat.css</x-css-link>
    @endsection

    @section('js')
    <x-js-script>websockets/ClientWebsocket.js</x-js-script>
    <x-js-script>websockets/websocket_standart.js</x-js-script>
    @endsection
    
    <div class="py-8 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 bg-white shadow-md">
            <div class='mb-6'>
                <h3 class='text-center font-bold mb-4 text-lg'>Число открытых заявок</h3>
                <div class='text-center font-bold mb-4 text-3xl text-rose-600'>{{$new_tasks_count}}</div>
            </div>
            <div>
                <h3 class='text-center font-bold mb-4 text-lg'>Статистика исполнителей</h3>
                <table class="stat-table w-100 mx-auto">
                    <tr class='bg-dark-theme color-light-theme w-100'>
                        <th class='px-5 py-2 text-lg'>{{$table_headers[0]}}</th>
                        <th class='text-center px-8 py-2 text-lg'>{{$table_headers[1]}}</th>
                        <th class='text-center px-8 py-2 text-lg'>{{$table_headers[2]}}</th>
                    </tr>

                    @foreach ($executors_stat_arr as $executor)
                    <tr class='w-100'>
                        <td class='px-4 py-4 py-2 text-lg border-b'>{{$executor['name']}}</td>
                        <td class='text-center text-lg border-b'>
                            <div class='stat-table__count'>{{$executor['process_count']}}</div>
                            <div class='stat-table__count-percent'>({{$executor['process_count_percent']}}%)</div> 
                        </td>
                        <td class='text-center text-lg border-b'>
                            <div class='stat-table__count'>{{$executor['completed_count']}}</divn>
                            <div class='stat-table__count-percent'>({{$executor['completed_count_percent']}}%)</div> 
                        </td>
                    </tr>
                    @endforeach

                    <tr class='w-100'>
                        <td class='px-4 py-4 text-lg border-b font-semibold'>Всего</td>
                        <td class='text-center text-lg border-b font-semibold'>{{$process_tasks_count}}</td>
                        <td class='text-center text-lg border-b font-semibold'>{{$completed_tasks_count}}</td>
                    </tr>
                </table>
            </div>
       </div>
    </div>
</x-app-layout>
