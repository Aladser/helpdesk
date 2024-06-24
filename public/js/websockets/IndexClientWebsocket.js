/** Клиентский вебсокет индексной страницы*/
class IndexClientWebsocket extends ClientWebsocket {
    /**
     * @param {*} websocket_url - адрес вебсокета
     * @param {*} user_login - логин пользователя
     */
    constructor(websocket_url, user_login = null, user_role = null) {
        super(websocket_url);
        // имя текущего пользователя
        this.user_login = user_login;
        // роль текущего пользователя
        this.user_role = user_role;
    }

    onOpen(e) {
        console.log(
            `Соединение ${this.user_login} с вебсокетом ${this.websocket_addr} установлено.`
        );
    }

    // получение сообщений
    onMessage(e) {
        try {
            let server_data = JSON.parse(e.data);
            switch (server_data.type) {
                case "onconnection":
                    server_data.user_login = this.user_login;
                    server_data.user_role = this.user_role;
                    this.sendData(server_data);
            }
        } catch (e) {
            console.log(e);
            alert("ошибка парсинга сообщения вебсокета");
        }
    }
}
