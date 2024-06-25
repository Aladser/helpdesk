/** базовый класс клиентского вебсокета */
class ShowClientWebsocket extends ClientWebsocket{
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
