const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]');
const TASK_ID = document.querySelector("#task__id").value;

const TASK_NODE = document.querySelector("#task");
const TASK_STATUS_NODE = document.querySelector("#task__status");
const TASK_EXECUTOR_NODE = document.querySelector("#task__executor");

const NEW_CMT_FORM_BLOCK = document.querySelector("#new-cmt-form-block");
const NEW_CMT_FORM = document.querySelector("#new-cmt-form");
const NEW_CMT_FORM_MSG_FILED = document.querySelector(
    "#new-cmt-form__msg-field"
);
const CMT_LIST_BLOCK = document.querySelector("#cmt-list-block");

const TASK_BTN_BLOCK = document.querySelector("#task__btn-block");
let take_task_btn = document.querySelector("#btn-take-task");
let complete_task_btn = document.querySelector("#btn-complete-task");

//-----взять в работу-----
if (take_task_btn) {
    take_task_btn.addEventListener("click", () =>
        sendUpdateTaskStatus(TASK_ID, "take-task")
    );
} else {
    //-----выполнить работу-----
    if (complete_task_btn) {
        complete_task_btn.addEventListener("click", () =>
            sendUpdateTaskStatus(TASK_ID, "complete-task")
        );
    }
}

// сохранить комментарий------
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
/**Обновить статус задачи*/
function sendUpdateTaskStatus(task_id, action) {
    let headers = {
        "X-CSRF-TOKEN": CSRF_TOKEN.getAttribute("content"),
        "Content-Type": "application/json",
    };

    let params = {};
    params.action = action;
    params.id = task_id;

    ServerRequest.execute({
        URL: `/task/${task_id}`,
        processFunc: (data) => handleUpdateTaskStatus(data),
        method: "put",
        data: JSON.stringify(params),
        headers: headers,
    });
}

//**обработать ответ сервера на Обновить статус задачи*/
function handleUpdateTaskStatus(response) {
    let responseData = JSON.parse(response);

    if (responseData.is_updated === 1) {
        if (responseData["action"] == "take-task") {
            // взять задачу в работу

            NEW_CMT_FORM_BLOCK.classList.remove("hidden");
            // исполнитель
            // p id='task__executor' class='mb-2'>Исполнитель: {{$task->executor->full_name()}}</p>
            let executor_node = document.createElement("p");
            executor_node.id = "task__executor";
            executor_node.className = "mb-2";
            executor_node.textContent = `Исполнитель: ${responseData.executor}`;
            TASK_NODE.append(executor_node);

            // статус
            TASK_STATUS_NODE.textContent = "В работе";
            TASK_STATUS_NODE.classList.remove("text-rose-600");
            TASK_STATUS_NODE.classList.add("text-amber-500");

            // кнопка Выполнить
            // <button id='btn-complete-task'class='border px-4 py-2 rounded bg-dark-theme color-light-theme'>Выполнить</button>
            complete_task_btn = document.createElement("button");
            complete_task_btn.id = "btn-complete-task";
            complete_task_btn.className =
                "border px-4 py-2 rounded bg-dark-theme color-light-theme";
            complete_task_btn.textContent = "Выполнить";
            complete_task_btn.addEventListener("click", () =>
                sendUpdateTaskStatus(TASK_ID, "complete-task")
            );
            TASK_BTN_BLOCK.removeChild(take_task_btn);
            TASK_BTN_BLOCK.appendChild(complete_task_btn);
        } else if (responseData["action"] == "complete-task") {
            // статус
            NEW_CMT_FORM_BLOCK.classList.add("hidden");
            TASK_STATUS_NODE.textContent = "Выполнена";
            TASK_STATUS_NODE.classList.remove("text-amber-500");
            TASK_STATUS_NODE.classList.add("text-green-500");
            TASK_BTN_BLOCK.removeChild(complete_task_btn);
        }
    }
}

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
    let responseData = JSON.parse(response);
    if (response) {
        let comment_node = document.createElement("div");
        comment_node.className = "cmt-list-block__comment";
        comment_node.innerHTML = `
            <div>
                <div class="cmt-list-block__author color-lighter-theme">${responseData.executor_name}</div>
                <div class="cmt-list-block__time">${responseData.created_at}</div>
            </div>
            <div>${responseData.message}</div>
        `;
        CMT_LIST_BLOCK.prepend(comment_node);
        NEW_CMT_FORM.message.value = "";
    }
}
