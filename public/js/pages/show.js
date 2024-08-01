/**node-узел задачи*/
let task_node = document.querySelector("#task");
// кнопки узла задачи
let take_task_btn = task_node.querySelector("#btn-take-task");
let complete_task_btn = task_node.querySelector("#btn-complete-task");
let appoint_task_btn = task_node.querySelector("#btn-reassign-task");

let new_comment_form = document.querySelector("#new-cmt-form");
let new_comment_form_textarea = new_comment_form.querySelector("#new-comment-form__textarea");
/**блок комментариев*/
let comment_list_block = document.querySelector("#cmt-list-block");
let new_comment_form_block = document.querySelector("#new-cmt-form-block");

/**блок назначения задачи инженеру*/
let appoint_user_block = document.querySelector("#reassign-user-list-block");
/**кнопка "Подтвердить назначить задачи"*/
let apply_appoint_user_btn = false;
/**select списка техспецов*/
let appoint_user_select = false;
if( appoint_user_block) {
    appoint_user_select = appoint_user_block.querySelector("#reassign-user-form__select");
    apply_appoint_user_btn = appoint_user_block.querySelector("#reassign-user-list-block__btn-appoint");
}

/**обработчик обновления статуса задачи*/
let updateTaskStatusHandler = new UpdateTaskStatusHandler(
    task_node,
    new_comment_form_block,
    comment_list_block,
    document.querySelector('meta[name="csrf-token"]')
);

/**массив [id: техспециалист]*/
let techsupport_arr = new Map();
Array.from(document.querySelectorAll('#reassign-user-form__select option')).forEach(spec => {
    if(spec.id) {
        // вырезает 'executor-{ID}'
        techsupport_arr[spec.value] = spec.id.slice(9); 
    }
})


// ----- <ДОБАВЛЕНИЕ ИЗОБРАЖЕНИЯ В КОММЕНТАРИЙ> -----
/** Список загружаемых файлов */
let uploaded_image_array = {};
/**кнопка выбора изображения*/
let select_image_btn = document.querySelector('#block-submit__image-input-btn');
/**input выбора файла*/
let select_image_input = document.querySelector('#block-submit__image-input');
/**блок прикрепленных изображений*/
let new_cmt_form_img_block = document.querySelector('#new-cmt-form__img-block');
select_image_btn.onclick = () =>  select_image_input.click();

select_image_input.addEventListener('change', function(e){
    new_cmt_form_img_block.classList.remove('hidden');
    
    for(let i=0 ;i<this.files.length; i++) {
        // создание блока изображения
        let img_block = document.createElement('div');
        img_block.className = 'new-cmt-form__img-elem me-2 flex' 

        // создание изображения
        let img = document.createElement('img');
        img.className = 'object-cover h-32 me-1';
        img.src = URL.createObjectURL(this.files[i]);
        img_block.append(img);

        // создание крестика
        let button = document.createElement('button');
        button.title = 'удалить изображение';
        button.textContent = 'X';
        img_block.append(button);

        new_cmt_form_img_block.append(img_block);
        
        // кнопка удаления изображения
        button.onclick = () => {
            let img_elem = button.closest('.new-cmt-form__img-elem');
            let img_src = img_elem.querySelector('img').src;
            delete uploaded_image_array[img_src];
            img_elem.remove();
        };

        uploaded_image_array[img.src] = this.files[i];
    }
});
// ----- </ДОБАВЛЕНИЕ ИЗОБРАЖЕНИЯ В КОММЕНТАРИЙ> -----


/**-- ОТПРАВКА КОММЕНТАРИЕВ НА СЕРВЕР --*/
let storeCommentHandler = new StoreCommentHandler(new_comment_form, comment_list_block, uploaded_image_array, new_cmt_form_img_block);


// ----- адрес вебсокета -----
const WEBSOCKET_ADDRESS = document.querySelector("meta[name='websocket']").content;
const USER_LOGIN = document.querySelector("meta[name='login']").content;
const USER_ROLE = document.querySelector("meta[name='role']").content;
const websocket = new ShowClientWebsocket(WEBSOCKET_ADDRESS, USER_LOGIN, USER_ROLE, comment_list_block);


// выбран специалист для назначения задачи
appoint_user_select.onchange = () => apply_appoint_user_btn.disabled = false;

// сохранить комментарий
new_comment_form.addEventListener("submit", function (e) {
    e.preventDefault();
    if(this.message.value == '' && this.images.files.length == 0) {
        // если пустые поля
        return;
    }
    storeCommentHandler.send(e, new FormData(this));
});


let isShiftPressed = false;
// отпускание клавиши Shift или Enter
new_comment_form_textarea.addEventListener("keyup", function (e) {
    if (e.key == "Enter" && !isShiftPressed) {
        // отправка комментария
        let formData = new FormData();
        formData.append("_token", new_comment_form._token.value);
        formData.append("message", new_comment_form.message.value);
        formData.append("task_id", new_comment_form.task_id.value);
        storeCommentHandler.send(e, formData);
    } else if (e.key == "Shift") {
        // Shift + Enter
        isShiftPressed = false;
    }
});
// Нажатие Shift
new_comment_form_textarea.addEventListener("keydown", function (e) {
    if (e.key == "Shift") {
        isShiftPressed = true;
    }
});


/**кнопка скрытия формы "Назначить задачу"*/
let hide_appoint_user_form_btn = document.querySelector("#reassign-user-list-block__btn-cancel");
// отменить показ формы "Назначить ответственного"
if(hide_appoint_user_form_btn) {
    hide_appoint_user_form_btn.onclick = () => {
        appoint_task_btn.disabled = false;;
        appoint_user_block.classList.add("hidden");
        if(take_task_btn) {
            take_task_btn.classList.remove('hidden');
        } else {
            complete_task_btn.classList.remove('hidden');
        }
    };
}

// Назначить ответственного за задачу - обновление верстки
if (appoint_task_btn) {
    appoint_task_btn.onclick = () => {
        appoint_task_btn.disabled = true;
        appoint_user_block.classList.remove("hidden");
        if(take_task_btn) {
            take_task_btn.classList.add('hidden');
        }
        if(complete_task_btn){
            complete_task_btn.classList.add('hidden');
        }
    };
}

// назначить ответственного за задачу - отправка запроса
apply_appoint_user_btn.onclick = () => {
    let user_id = techsupport_arr[appoint_user_select.value];
    hide_appoint_user_form_btn.click();
    updateTaskStatusHandler.send('take-task', null, user_id);
};