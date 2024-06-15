const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]');
const TASK_ID = document.querySelector('#task__id').value;

const TASK_NODE = document.querySelector('#task');
const TASK_STATUS_NODE = document.querySelector('#task__status');
const TASK_EXECUTOR_NODE = document.querySelector('#task__executor');

const COMMENT_LIST_CONTAINER_NODE = document.querySelector('#comment-list-container');

let task_btn_block = document.querySelector('#task__btn-block');
let take_task_btn = document.querySelector('#btn-take-task');
let complete_task_btn = document.querySelector('#btn-complete-task');

//-----взять в работу-----
if(take_task_btn) {
    take_task_btn.addEventListener('click', () => sendUpdateRequest(TASK_ID, 'take-task'));
} else {
    //-----выполнить работу-----
    if(complete_task_btn) {
        complete_task_btn.addEventListener('click', () => sendUpdateRequest(TASK_ID, 'complete-task'));
    }
}

/**отправить запрос на обновление ресурсов*/
function sendUpdateRequest(task_id, action){
    let headers = {
        "X-CSRF-TOKEN": CSRF_TOKEN.getAttribute("content"),
        "Content-Type": "application/json",
    };

    let params = {};
    params.action = action;
    params.id = task_id;

    ServerRequest.execute(
        `/task/${task_id}`,
        (data) => handleTask(data),
        "put",
        null,
        JSON.stringify(params),
        headers
    );
}

//**обработать ответа сервера*/
function handleTask(response) {
    let responseData = JSON.parse(response);

    if (responseData.is_updated === 1) {
        if(responseData['action'] == 'take-task') {
            // взять задачу в работу

            COMMENT_LIST_CONTAINER_NODE.classList.remove('hidden');
            // исполнитель
            // p id='task__executor' class='mb-2'>Исполнитель: {{$task->executor->full_name()}}</p>
            let executor_node = document.createElement('p');
            executor_node.id = 'task__executor';
            executor_node.className = 'mb-2';
            executor_node.textContent = `Исполнитель: ${responseData.executor}`;
            TASK_NODE.append(executor_node);

            // статус
            TASK_STATUS_NODE.textContent = 'В работе';
            TASK_STATUS_NODE.classList.remove('text-rose-600');
            TASK_STATUS_NODE.classList.add('text-amber-500');

            // кнопка Выполнить
            // <button id='btn-complete-task'class='border px-4 py-2 rounded bg-dark-theme color-light-theme'>Выполнить</button>
            complete_task_btn = document.createElement('button');
            complete_task_btn.id = 'btn-complete-task';
            complete_task_btn.className = 'border px-4 py-2 rounded bg-dark-theme color-light-theme';
            complete_task_btn.textContent = 'Выполнить';
            complete_task_btn.addEventListener('click', () => sendUpdateRequest(TASK_ID, 'complete-task'));
            task_btn_block.removeChild(take_task_btn);
            task_btn_block.appendChild(complete_task_btn);
        } else if(responseData['action'] == 'complete-task') {
            // статус
            TASK_STATUS_NODE.textContent = 'Выполнена';
            TASK_STATUS_NODE.classList.remove('text-amber-500');
            TASK_STATUS_NODE.classList.add('text-green-500');
            task_btn_block.removeChild(complete_task_btn);
        }
    }
}
