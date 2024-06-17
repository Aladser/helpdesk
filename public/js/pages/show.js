let new_comment_form = document.querySelector("#new-cmt-form");
let new_cmt_from_msg_field = document.querySelector("#new-cmt-form__msg-field");
let comment_list_block = document.querySelector("#cmt-list-block");

let task_node = document.querySelector("#task");
let new_comment_form_block = document.querySelector("#new-cmt-form-block");
let csrf_toke_node = document.querySelector('meta[name="csrf-token"]');
let updateTaskStatusHandler = new UpdateTaskStatusHandler(task_node, new_comment_form_block, csrf_toke_node);
let storeCommentHandler = new StoreCommentHandler(new_comment_form, comment_list_block);

//---сохранить комментарий---
new_comment_form.addEventListener("submit", function (e) {
    storeCommentHandler.send(e, new FormData(this));
});
// отпускание клавиши Shift или Enter
let pressed_keys = [];
new_cmt_from_msg_field.addEventListener("keyup", function (e) {
    if (e.key == "Enter" && !pressed_keys.includes("Shift")) {
        let formData = new FormData();
        formData.append("_token", new_comment_form._token.value);
        formData.append("message", new_comment_form.message.value);
        formData.append("task_id", new_comment_form.task_id.value);
        storeCommentHandler.send(e, formData);
    } else if (e.key == "Shift") {
        pressed_keys.pop();
    }
});
// Нажатие Shift
new_cmt_from_msg_field.addEventListener("keydown", function (e) {
    if (e.key == "Shift") {
        pressed_keys.push("Shift");
    }
});