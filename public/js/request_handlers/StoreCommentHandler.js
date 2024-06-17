/**Клиентский обработчик отправки комментариев*/
class StoreCommentHandler{
    /**
     * @param {*} new_comment_form форма отправки комментария
     * @param {*} comment_list_block блок комментариев
     */
    constructor(new_comment_form, comment_list_block) {
        this.new_comment_form = new_comment_form;
        this.comment_list_block = comment_list_block;
    }

    /**cохранить комментарий в БД*/
    send(e, formData) {
        e.preventDefault();
        ServerRequest.execute({
            URL: "/comment",
            processFunc: (data) => this.handle(data),
            method: "post",
            data: formData,
        });
    }

    /**обработать ответ сервера на Сохранить комментарий в БД*/
    handle(response) {
        /*
        <div class="cmt-list-block__comment">
            <div>
                <div class="cmt-list-block__author text-amber-500">Хохлова А. В.</div>
                <div class="cmt-list-block__time">2024-06-16 08:56</div>
            </div>
            <div>cообщение</div>
        </div>
        */

        let response_data = JSON.parse(response);
        if (response_data.is_stored) {
            let comment_node = document.createElement("div");
            comment_node.className = "cmt-list-block__comment";
            let author_classname = "cmt-list-block__author ";
            author_classname +=
                response_data.author_role == "executor"
                    ? "color-lighter-theme"
                    : "text-amber-500";
            comment_node.innerHTML = `
                <div>
                    <div class="${author_classname}">${response_data.author_name}</div>
                    <div class="cmt-list-block__time">${response_data.created_at}</div>
                </div>
                <div>${response_data.message}</div>
            `;
            this.comment_list_block.prepend(comment_node);
            this.new_comment_form.message.value = "";
        }
    }
}