const TASK_ELEMENT_URL_PART = '/task';
const TASK_ROWS = document.querySelectorAll('.task-table__row');

/**переходы на страницу задачи*/
TASK_ROWS.forEach(task => {
    task.onclick = function() {
        id = this.id.slice(this.id.indexOf(4));
        window.location.href = `${TASK_ELEMENT_URL_PART}/${id}`;
    }
});