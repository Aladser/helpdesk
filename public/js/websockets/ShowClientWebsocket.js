/** базовый класс клиентского вебсокета */
class ShowClientWebsocket extends ClientWebsocket{
    constructor(websocket_url, user_login, user_role, comment_list_block) {
        super(websocket_url, user_login, user_role);
        this.comment_list_block = comment_list_block;
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
                case 'comment-new':
                    this.createCommentNode(server_data)
                    break;
            }
        } catch (e) {
            console.log(e);
            alert("Ошибка парсинга сообщения вебсокета");
        }
    }

    createCommentNode(comment_obj) {
        let comment_node = document.createElement('div');
        comment_node.className = 'cmt-list-block__comment';
        let author_color_class = comment_obj.author_role=='executor' ? 'color-lighter-theme' : 'text-amber-500';
        
        let comment_node_content = `
            <div>
                <div class='cmt-list-block__author ${author_color_class}'>${comment_obj.author_name}</div>
                <div class='cmt-list-block__time'>${comment_obj.created_at}</div>
            </div>
            <div>
        `;
        if(comment_obj.is_report) {
            comment_node_content += `
                <div class='text-green-500 font-semibold'>Задача выполнена</div>
            `;
        }
        comment_node_content += (comment_obj.content + '</div>');
        comment_node.innerHTML = comment_node_content;
        this.comment_list_block.prepend(comment_node);
    }
}

