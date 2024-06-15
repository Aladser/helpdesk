const TASK_ID = document.querySelector('#task-id').value;
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]');

//-----взять в работу-----
const TAKE_TASK_BTN = document.querySelector('#btn-take-task');
if(TAKE_TASK_BTN) {
    TAKE_TASK_BTN.addEventListener('click', () => sendUpdateRequest(TASK_ID, 'take-task'));
} else {
    //-----выполнить работу-----
    const COMPLETE_TASK_BTN = document.querySelector('#btn-complete-task');
    if(COMPLETE_TASK_BTN) {
        COMPLETE_TASK_BTN.addEventListener('click', () => sendUpdateRequest(TASK_ID, 'complete-task'));
    }
}

//-----отправить запрос на обновление ресурсов-----
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

//-----обработать ответа сервера-----
function handleTask(request) {
    console.log(request);
}
