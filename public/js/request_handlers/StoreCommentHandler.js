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
        for (let i=0; i<this.new_comment_form.images.files.length; i++) {
            formData.append('images[]', this.new_comment_form.images.files[i])
        }

        ServerRequest.execute({
            URL: "/comment",
            processFunc: (data) => this.handle(data),
            method: "post",
            data: formData,
        });
    }

    /**обработать ответ сервера на Сохранить комментарий в БД*/
    handle(response) {
        
        // ------- TEST------
        console.log(response);
        return;
        // -----------------

        let response_data = JSON.parse(response);
        if (response_data.is_stored) {    
            this.new_comment_form.message.value = "";
        }
    }
}
