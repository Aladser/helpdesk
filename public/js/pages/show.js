/**node-узел задачи*/
let task_node = document.querySelector("#task");
// кнопки узла задачи
let take_task_btn = task_node.querySelector("#btn-take-task");
let complete_task_btn = task_node.querySelector("#btn-complete-task");
let appoint_task_btn = task_node.querySelector("#btn-reassign-task");

let new_comment_form = document.querySelector("#new-cmt-form");
let new_comment_form_textarea = new_comment_form.querySelector("#new-comment-form__textarea");
let comment_list_block = document.querySelector("#cmt-list-block");
let new_comment_form_block = document.querySelector("#new-cmt-form-block");
/**блок назначения задачи инженеру*/
let appoint_user_block = document.querySelector("#reassign-user-list-block");
/**кнопка "Подтвердить назначить задачи"*/
let apply_appoint_user_btn = appoint_user_block.querySelector("#reassign-user-list-block__btn-appoint");
/**select списка техспецов*/
let appoint_user_select = appoint_user_block.querySelector("#reassign-user-form__select");
/**кнопка скрытия формы "Назначить задачу"*/
let hide_appoint_user_form_btn = document.querySelector("#reassign-user-list-block__btn-cancel");
/**обработчик обновления статуса задачи*/
let updateTaskStatusHandler = new UpdateTaskStatusHandler(
    task_node,
    new_comment_form_block,
    comment_list_block,
    document.querySelector('meta[name="csrf-token"]')
);
/**обработчик отправки комментария*/
let storeCommentHandler = new StoreCommentHandler(
    new_comment_form,
    comment_list_block
);
/**массив [id: техспециалист]*/
let techsupport_arr = new Map();
Array.from(document.querySelectorAll('#reassign-user-form__select option')).forEach(spec => {
    if(spec.id) {
        // вырезает 'executor-{ID}'
        techsupport_arr[spec.value] = spec.id.slice(9); 
    }
})
//-------------------------------------------------------------------------------------------


// выбран специалист для назначения задачи
appoint_user_select.onchange = () => apply_appoint_user_btn.disabled = false;

// сохранить комментарий
new_comment_form.addEventListener("submit", function (e) {
    storeCommentHandler.send(e, new FormData(this));
});


let isShiftPressed = false;
// отпускание клавиши Shift или Enter
new_comment_form_textarea.addEventListener("keyup", function (e) {
    if (e.key == "Enter" && !isShiftPressed) {
        let formData = new FormData();
        formData.append("_token", new_comment_form._token.value);
        formData.append("message", new_comment_form.message.value);
        formData.append("task_id", new_comment_form.task_id.value);
        storeCommentHandler.send(e, formData);
    } else if (e.key == "Shift") {
        isShiftPressed = false;
    }
});
// Нажатие Shift
new_comment_form_textarea.addEventListener("keydown", function (e) {
    if (e.key == "Shift") {
        isShiftPressed = true;
    }
});


// Назначить ответственного
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
// отменить показ формы "Назначить ответственного"
hide_appoint_user_form_btn.onclick = () => {
    appoint_task_btn.disabled = false;;
    appoint_user_block.classList.add("hidden");
    if(take_task_btn) {
        take_task_btn.classList.remove('hidden');
    } else {
        complete_task_btn.classList.remove('hidden');
    }
};

apply_appoint_user_btn.onclick = () => {
    let user_id = techsupport_arr[appoint_user_select.value];
    hide_appoint_user_form_btn.click();
    updateTaskStatusHandler.send('take-task', null, user_id);
};