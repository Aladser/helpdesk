/** базовый класс клиентского вебсокета */
class ClientWebsocket {
    constructor(websocket_url, user_login, user_role) {
        this.user_login = user_login;
        this.user_role = user_role;
        this.queue = [];

        this.websocket_addr = websocket_url;
        try {
            this.websocket = new WebSocket(websocket_url);
        } catch(e) {
            alert(e);
        }

        this.websocket.onerror = (e) => this.onError(e);
        this.websocket.onmessage = (e) => this.onMessage(e);
        this.websocket.onopen = (e) => this.onOpen(e);

        // Обработка события открытия соединения - отправление очереди сообщений
        this.websocket.addEventListener("open", () => {
            this.queue.forEach((message) => {
                this.sendData(message);
            });
            this.queue = [];
        });
    }

    onOpen(e) {
        console.log(
            `Соединение ${this.user_login} с вебсокетом ${this.websocket_addr} установлено.`
        );
    }

    // получение ошибок вебсокета
    onError(e) {
        let msg = `Ошибка соединения вебсокета ${this.websocket_addr}`;
        alert(msg);
        console.log(msg);
    }

    // получение сообщений
    onMessage(e) {
        try {
            let server_data = JSON.parse(e.data);
            //console.log(server_data);

            switch (server_data.type) {
                case "onconnection":
                    // установление соединения: отправка данных подключаемого пользователя
                    server_data.user_login = this.user_login;
                    server_data.user_role = this.user_role;
                    this.sendData(server_data);
                    break;
            }
        } catch (e) {
            console.log(e);
            alert("Ошибка парсинга сообщения вебсокета");
        }
    }

    // отправка сообщений
    sendData(data) {
        try {
            this.websocket.send(JSON.stringify(data));
        } catch (e) {
            this.queue.push(data);
        }
    }
}
