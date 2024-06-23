/** базовый класс клиентского вебсокета */
class ClientWebsocket {
    constructor() {
        // имя текущего пользователя
        let userElement = document.querySelector('meta[name="username"]');
        if (userElement) {
            this.username = userElement.content;
        }
        // адрес вебсокета
        let appUrl = document.querySelector('meta[name="websocket"]').content;
        // клиентский вебсокет
        this.websocket = new WebSocket(appUrl);
        this.websocket.onerror = (e) => this.onError(e);
        this.websocket.onmessage = (e) => this.onMessage(e);
        this.websocket.onopen = (e) => this.onOpen(e);
    }

    // получение ошибок вебсокета
    onError(e) {
        alert(
            `WebSocket connection ${
                document.querySelector('meta[name="websocket"]').content
            } failed`
        );
    }

    // получение сообщений
    onMessage(e) {
        let data = JSON.parse(e.data);
        //console.log(data);
        alert("метод onMessage вебсокета ClientWebsocket не реализован");
    }

    // отправка сообщений
    sendData(data) {
        this.websocket.send(JSON.stringify(data));
    }

    onOpen(e) {
        console.log(
            `Соединение с вебсокетом ${
                document.querySelector('meta[name="websocket"]').content
            } установлено.`
        );
    }
}
