const TASK_ELEMENT_URL_PART = '/task';
const TASK_ROWS = document.querySelectorAll('.task-table__row');

/**переходы на страницу задачи*/
TASK_ROWS.forEach(task => {
    task.onclick = function() {window.location.href = `${TASK_ELEMENT_URL_PART}/${this.id}`;}
});
