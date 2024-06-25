/** базовый класс клиентского вебсокета */
class ShowClientWebsocket extends ClientWebsocket{
    constructor(websocket_url, user_login, user_role) {
        super(websocket_url);
        this.user_login = user_login;
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
                    break;
                default:
                   console.log(server_data);
            }
        } catch (e) {
            console.log(e);
            alert("Ошибка парсинга сообщения вебсокета");
        }
    }
}
