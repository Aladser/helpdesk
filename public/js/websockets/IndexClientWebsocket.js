/** Клиентский вебсокет индексной страницы*/
class IndexClientWebsocket extends ClientWebsocket {
    /**цвет статуса*/
    static task_status_colors = {
        'Новая':'text-rose-600',
        'В работе':'text-amber-500',
        'Выполнена':'text-green-500',
    };

    constructor(websocket_url, user_login, user_role, tasks_table_node, task_filter_node) {
        super(websocket_url);
        this.user_login = user_login;
        this.user_role = user_role;

        this.tasks_table_node = tasks_table_node;
        this.selected_filter = task_filter_node.querySelector('input:checked').value;
        
        // фильтр типа заявок для исполнителей
        let belongs_filter_form = document.querySelector("#belongs-filter-form");
        if(belongs_filter_form) {
            this.belongs_filter = belongs_filter_form.belongs;
        }

    }

    onOpen(e) {
        console.log(
            `Соединение ${this.user_login} с вебсокетом ${this.websocket_addr} установлено.`
        );
    }

    onMessage(e) {
        try {
            let server_data = JSON.parse(e.data);

            switch (server_data.type) {
                case "onconnection":
                    server_data.user_login = this.user_login;
                    server_data.user_role = this.user_role;
                    this.sendData(server_data);
                    break;
                case 'task-new':
                    if(['new','all'].includes(this.selected_filter)) {
                        this.create_task_node(server_data, 'Новая');
                    }
                    break;
                case 'take-task':
                    this.updateTask(server_data);
                    break;
                case 'complete-task':
                    this.completeTask(server_data);
                    break;
                default:
                   console.log(server_data);
            }
        } catch (e) {
            console.log(e);
            alert("Ошибка парсинга сообщения вебсокета");
        }
    }

    // задача взята в работу
    updateTask(task_obj) {
        let task_node = this.tasks_table_node.querySelector('#task-'+task_obj.id);

        if(task_node) {
            if(this.selected_filter == 'all') {
                // 'все' + 'есть на странице'
                this.update_task_node(task_node, task_obj, 'В работе');
            } else if(this.selected_filter == 'new') {
                // 'новые' + 'есть на странице'
                task_node.remove();
            }
        } else if(this.selected_filter == 'process' && this.belongs_filter.value == 'all') {
            // 'в работе' + 'все' 
            this.create_task_node(task_obj, 'В работе');
        }
    }

    // завершена задача
    completeTask(task_obj) {
        let task_node = this.tasks_table_node.querySelector('#task-'+task_obj.id);

        if(task_node) {
            if(this.selected_filter == 'process') {
                // 'в работе' + 'есть на странице'
                task_node.remove();
            } else if(this.selected_filter == 'all') {
                // 'все' + 'есть на странице'
                this.update_task_node(task_node, task_obj, 'Выполнена');
            }
        } else if(this.selected_filter == 'completed' && this.belongs_filter.value == 'all') {
            // 'завершена'
            this.create_task_node(task_obj, 'Выполнена');
        }
    }

    // создает элемент задачи
    create_task_node(task_obj, status) {
        // статус задачи
        let status_classname = IndexClientWebsocket.task_status_colors[status];
        let executor_name = task_obj.executor_name ? task_obj.executor_name : '';

        let task_node = document.createElement('tr');
        task_node.className = 'task-table__row';
        task_node.id = 'task-'+task_obj.id;
        task_node.innerHTML = `
            <td class='text-center'>${task_obj.id}</td>
            <td> 
                <a class='task-table__link' href="/task/${task_obj.id}" class='underline w-1/3 block h-full w-full'>${task_obj.header}</a> 
            </td>
            <td class='text-center'>${task_obj.author_name}</td>
            <td class='text-center'>${task_obj.created_at}</td>
            <td class='text-center'>${executor_name}</td>
            <td class='text-center font-semibold ${status_classname}'>${status}</td>
            <td class='text-center'>${task_obj.updated_at}</td>
        `;

        this.tasks_table_node.querySelector('tr').after(task_node);
    }

    // обновляет элемент задачи
    update_task_node(task_node, task_obj, status) {
        task_node.querySelectorAll('td')[4].textContent = task_obj.executor_name;

        let status_classname = IndexClientWebsocket.task_status_colors[status];
        let task_status_node = task_node.querySelectorAll('td')[5];
        task_status_node.textContent = status;
        task_status_node.className = 'text-center font-semibold ' + status_classname;

        task_node.querySelectorAll('td')[6].textContent = task_obj.updated_at;
    }
}

