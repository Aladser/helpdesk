/**форма фильтра*/
let filter_form = document.querySelector("#task-filter-form");
/** фильтр принадлежности задач*/
let belongs_filter_form = document.querySelector("#belongs-filter-form");
let belongs_filter_form_select = belongs_filter_form.querySelector("#belongs-filter-form__select");
/**кнопки фильтра задач*/
let filter_switchers = new Map([
    ["all", document.querySelector("#task-filter-form__all")],
    ["new", document.querySelector("#task-filter-form__new")],
    ["process", document.querySelector("#task-filter-form__process")],
    ["completed", document.querySelector("#task-filter-form__completed")],
]);

filter_switchers.forEach(switcher => switcher.onclick = get_tasks_index);
belongs_filter_form_select.onchange = get_tasks_index;

/**получить страницу задач*/
function get_tasks_index() {
    let task_type = filter_form.querySelector("input:checked").value;
    let task_belongs = belongs_filter_form.belongs.value;
    window.open(`/task?type=${task_type}&belongs=${task_belongs}`, '_self');
}
