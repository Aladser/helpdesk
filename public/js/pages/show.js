const NEW_CMT_FORM = document.querySelector("#new-cmt-form");
const NEW_CMT_FORM_MSG_FILED = document.querySelector("#new-cmt-form__msg-field");
const CMT_LIST_BLOCK = document.querySelector("#cmt-list-block");

let task_node = document.querySelector("#task");
let new_comment_form_block = document.querySelector("#new-cmt-form-block");
let csrf_toke_node = document.querySelector('meta[name="csrf-token"]');
let updateTaskStatusHandler = new UpdateTaskStatusHandler(task_node, new_comment_form_block, csrf_toke_node);

//---сохранить комментарий---
NEW_CMT_FORM.addEventListener("submit", function (e) {
    sendStoreComment(e, new FormData(this));
});
// отпускание клавиши Shift или Enter
let pressed_keys = [];
NEW_CMT_FORM_MSG_FILED.addEventListener("keyup", function (e) {
    if (e.key == "Enter" && !pressed_keys.includes("Shift")) {
        let formData = new FormData();
        formData.append("_token", NEW_CMT_FORM._token.value);
        formData.append("message", NEW_CMT_FORM.message.value);
        formData.append("task_id", NEW_CMT_FORM.task_id.value);
        sendStoreComment(e, formData);
    } else if (e.key == "Shift") {
        pressed_keys.pop();
    }
});
// Нажатие Shift
NEW_CMT_FORM_MSG_FILED.addEventListener("keydown", function (e) {
    if (e.key == "Shift") {
        pressed_keys.push("Shift");
    }
});

// --------- ФУНКЦИИ --------
/**Сохранить комментарий в БД*/
function sendStoreComment(e, formData) {
    e.preventDefault();
    ServerRequest.execute({
        URL: "/comment",
        processFunc: (data) => handleStoreComment(data),
        method: "post",
        data: formData,
    });
}

/**обработать ответ сервера на Сохранить комментарий в БД*/
function handleStoreComment(response) {
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
        CMT_LIST_BLOCK.prepend(comment_node);
        NEW_CMT_FORM.message.value = "";
    }
}
