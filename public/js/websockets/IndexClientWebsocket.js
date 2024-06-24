/** Клиентский вебсокет индексной страницы*/
class IndexClientWebsocket extends ClientWebsocket {
    constructor(websocket_url, user_login, user_role, tasks_table_node) {
        super(websocket_url);
        this.user_login = user_login;
        this.user_role = user_role;

        this.tasks_table_node = tasks_table_node;
        this.tasks_header_node = tasks_table_node.querySelector('tr');
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
                default:
                    this.showMessage(server_data);
            }
        } catch (e) {
            console.log(e);
            alert("ошибка парсинга сообщения вебсокета");
        }
    }

    showMessage(task_obj) {
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
                <td class='text-center'></td>
                <td class='text-center font-semibold text-rose-600'>Новая</td>
                <td class='text-center'>${task_obj.updated_at}</td>
        `;
        this.tasks_header_node.after(task_node);
    }
}

