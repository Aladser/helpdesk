/**форма фильтра*/
let filter_form = document.querySelector("#task-filter-form");
/** фильтр принадлежности задач*/
let belongs_filter_form = document.querySelector("#belongs-filter-form");
let belongs_filter_form_select = false;
if(belongs_filter_form) {
    belongs_filter_form_select = belongs_filter_form.querySelector("#belongs-filter-form__select");
}
/**кнопки фильтра задач*/
let filter_switchers = new Map([
    ["all", document.querySelector("#task-filter-form__all")],
    ["new", document.querySelector("#task-filter-form__new")],
    ["process", document.querySelector("#task-filter-form__process")],
    ["completed", document.querySelector("#task-filter-form__completed")],
]);

/**адрес вебсокета*/
const WEBSOCKET_ADDRESS = document.querySelector("meta[name='websocket']").content;
const USER_LOGIN = document.querySelector("meta[name='login']").content;
const USER_ROLE = document.querySelector("meta[name='role']").content;
const TABLE_NODE = document.querySelector('#task-table');
const TASK_FILTER_NODE = document.querySelector('#task-filter-form');
const websocket = new IndexClientWebsocket(
    WEBSOCKET_ADDRESS, 
    USER_LOGIN, USER_ROLE, 
    TABLE_NODE, TASK_FILTER_NODE
);

filter_switchers.forEach(switcher => switcher.onclick = get_tasks_page);
if(belongs_filter_form) {
    belongs_filter_form_select.onchange = get_tasks_page;
}

/**получить страницу задач */
function get_tasks_page() {
    let task_type_value = filter_form.querySelector("input:checked").value;
    if(belongs_filter_form) {
        let task_belongs_value = belongs_filter_form.belongs.value;
        window.open(`/task?type=${task_type_value}&belongs=${task_belongs_value}`, '_self');
    } else{
        window.open(`/task?type=${task_type_value}`, '_self');  
    }
}
