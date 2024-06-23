/** базовый класс клиентского вебсокета */
class ClientWebsocket {
    constructor(websocket_url) {
        this.websocket_addr = websocket_url;
        this.websocket = new WebSocket(websocket_url);
        this.websocket.onerror = (e) => this.onError(e);
        this.websocket.onmessage = (e) => this.onMessage(e);
        this.websocket.onopen = (e) => this.onOpen(e);
    }

    onOpen(e) {
        console.log(`Соединение с вебсокетом ${this.websocket_addr} установлено.`);
    }

    // получение ошибок вебсокета
    onError(e) {
        let msg = `Ошибка соединения вебсокета ${this.websocket_addr}`;
        alert(msg);
        console.log(msg);
    }

    // получение сообщений
    onMessage(e) {
        let data = JSON.parse(e.data);
        console.log(data);
    }

    // отправка сообщений
    sendData(data) {
        this.websocket.send(JSON.stringify(data));
    }
}
