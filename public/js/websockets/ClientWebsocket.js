/** базовый класс клиентского вебсокета */
class ClientWebsocket {
    constructor(appUrl, websocket_addr, username_node = null) {
        // имя текущего пользователя
        this.username = username_node;
        // клиентский вебсокет
        this.websocket_addr = websocket_addr;
        this.websocket = new WebSocket(appUrl);
        this.websocket.onerror = (e) => this.onError(e);
        this.websocket.onmessage = (e) => this.onMessage(e);
        this.websocket.onopen = (e) => this.onOpen(e);
    }

    // получение ошибок вебсокета
    onError(e) {
        let msg = `Ошибка соединения вебсокета ${websocket_addr}`;
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
    }

    onOpen(e) {
        console.log(`Соединение с вебсокетом ${websocket_addr} установлено.`);
    }
}
