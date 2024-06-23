/** базовый класс клиентского вебсокета */
class ClientWebsocket {
    constructor(websocket_url, user_login = null) {
        // имя текущего пользователя
        this.user_login = user_login;
        // клиентский вебсокет
        this.websocket_addr = websocket_url;
        this.websocket = new WebSocket(websocket_url);
        this.websocket.onerror = (e) => this.onError(e);
        this.websocket.onmessage = (e) => this.onMessage(e);
        this.websocket.onopen = (e) => this.onOpen(e);
    }

    // получение ошибок вебсокета
    onError(e) {
        let msg = `Ошибка соединения вебсокета ${this.websocket_addr}`;
        alert(msg);
        console.log(msg);
    }

    // получение сообщений
    onMessage(e) {
        //let data = JSON.parse(e.data);
        let msg = "Метод onMessage вебсокета не реализован";
        alert(msg);
        console.log(msg);
    }

    // отправка сообщений
    sendData(data) {
        this.websocket.send(JSON.stringify(data));
        let msg = "Метод sendData вебсокета не реализован";
        alert(msg);
        console.log(msg);
    }

    onOpen(e) {
        console.log(`Соединение ${this.user_login} с вебсокетом ${this.websocket_addr} установлено.`);
    }
}
