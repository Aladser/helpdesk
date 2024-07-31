/**Клиентский обработчик отправки комментариев*/
class StoreCommentHandler{
    /**
     * @param {*} new_comment_form форма отправки комментария
     * @param {*} comment_list_block блок комментариев
     */
    constructor(new_comment_form, comment_list_block, uploaded_image_array) {
        this.new_comment_form = new_comment_form;
        this.comment_list_block = comment_list_block;
        this.uploaded_image_array = uploaded_image_array;
    }

    /**cохранить комментарий в БД*/
    send(e, formData) {
        e.preventDefault();

        // загрузка изображений на сервер
        for (var key in this.uploaded_image_array) {
            formData.append('images[]', this.uploaded_image_array[key]);
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
            this.new_comment_form.reset();
        }
    }
}
