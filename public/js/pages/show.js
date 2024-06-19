let new_comment_form = document.querySelector("#new-cmt-form");
let new_comment_form_textarea = new_comment_form.querySelector(
    "#new-comment-form__textarea"
);
let comment_list_block = document.querySelector("#cmt-list-block");
let task_node = document.querySelector("#task");
let new_comment_form_block = document.querySelector("#new-cmt-form-block");
let csrf_toke_node = document.querySelector('meta[name="csrf-token"]');

let take_task_btn = task_node.querySelector("#btn-take-task");
let complete_task_btn = task_node.querySelector("#btn-complete-task");
let reassign_task_btn = document.querySelector("#btn-reassign-task");
let reassign_user_list_block = document.querySelector(
    "#reassign-user-list-block"
);
let reassign_task_btn_cancel = document.querySelector(
    "#reassign-user-list-block__btn-cancel"
);

let updateTaskStatusHandler = new UpdateTaskStatusHandler(
    task_node,
    new_comment_form_block,
    comment_list_block,
    csrf_toke_node
);
let storeCommentHandler = new StoreCommentHandler(
    new_comment_form,
    comment_list_block
);

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
reassign_task_btn.onclick = () => {
    reassign_task_btn.disabled = true;
    reassign_task_btn.classList.add('disabled:opacity-75');
    reassign_user_list_block.classList.remove("hidden");
    if(take_task_btn) {
        take_task_btn.classList.add('hidden');
    }
    if(complete_task_btn){
        complete_task_btn.classList.add('hidden');
    }
};
// отменить Назначить ответственного
reassign_task_btn_cancel.onclick = () => {
    reassign_task_btn.disabled = false;
    reassign_task_btn.classList.remove('disabled:opacity-75');
    reassign_user_list_block.classList.add("hidden");
    if(take_task_btn) {
        take_task_btn.classList.remove('hidden');
    } else {
        complete_task_btn.classList.remove('hidden');
    }
};