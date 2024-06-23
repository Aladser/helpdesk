/** Клиентский вебсокета индексной страницы*/
class IndexClientWebsocket extends ClientWebsocket {
    // получение сообщений
    onMessage(e) {
        //let data = JSON.parse(e.data);
        let msg = "Метод onMessage вебсокета не реализован";
        alert(msg);
        console.log(msg);
    }
}
