/**Клиентский обработчик обновления статуса задачи*/
class UpdateTaskStatusHandler {
    /**
     * @param {*} task_node узел задачи
     * @param {*} new_comment_form_block  узел блока формы отправки комментария
     * @param {*} csrf_token CSRF-токен
     */
    constructor(task_node, new_comment_form_block, csrf_token) {
        this.task_node = task_node;
        this.task_status_node = this.task_node.querySelector("#task__status");

        this.task_btn_block = this.task_node.querySelector("#task__btn-block");
        this.take_task_btn = this.task_node.querySelector("#btn-take-task");
        this.complete_task_btn = this.task_node.querySelector("#btn-complete-task");

        this.csrf_toke_node = csrf_token;
        this.new_cmt_form_block =  new_comment_form_block;
        this.task_id_node = this.new_cmt_form_block.querySelector("#task__id");

        //-----взять в работу-----
        if (this.take_task_btn) {
            this.take_task_btn.addEventListener("click", () =>
                this.send(this.task_id_node.value, "take-task")
            );
        } else {
            //-----выполнить работу-----
            if (this.complete_task_btn) {
                this.complete_task_btn.addEventListener("click", () =>
                    this.send(this.task_id_node.value, "complete-task")
                );
            }
        }
    }

    /**Обновить статус задачи*/
    send(task_id, action) {
        let headers = {
            "X-CSRF-TOKEN": this.csrf_toke_node.getAttribute("content"),
            "Content-Type": "application/json",
        };

        ServerRequest.execute({
            URL: `/task/${task_id}`,
            processFunc: (data) => this.handle(data),
            method: "put",
            data: JSON.stringify({ action: action }),
            headers: headers,
        });
    }

    //**обработать ответ сервера на Обновить статус задачи*/
    handle(response) {
        let responseData = JSON.parse(response);

        if (responseData.is_updated === 1) {
            if (responseData["action"] == "take-task") {
                // взять задачу в работу

                this.new_cmt_form_block.classList.remove("hidden");
                // исполнитель
                // p id='task__executor' class='mb-2'>Исполнитель: {{$task->executor->full_name()}}</p>
                let executor_node = document.createElement("p");
                executor_node.id = "task__executor";
                executor_node.className = "mb-2";
                executor_node.textContent = `Исполнитель: ${responseData.executor}`;
                this.task_node.append(executor_node);

                // статус
                this.task_status_node.textContent = "В работе";
                this.task_status_node.classList.remove("text-rose-600");
                this.task_status_node.classList.add("text-amber-500");

                // кнопка Выполнить
                // <button id='btn-complete-task'class='border px-4 py-2 rounded bg-dark-theme color-light-theme'>Выполнить</button>
                this.complete_task_btn = document.createElement("button");
                this.complete_task_btn.id = "btn-complete-task";
                this.complete_task_btn.className =
                    "border px-4 py-2 rounded bg-dark-theme color-light-theme";
                this.complete_task_btn.textContent = "Выполнить";
                this.complete_task_btn.addEventListener("click", () =>
                    this.send(this.task_id_node.value, "complete-task")
                );
                this.task_btn_block.removeChild(this.take_task_btn);
                this.task_btn_block.appendChild(this.complete_task_btn);
            } else if (responseData["action"] == "complete-task") {
                // статус
                this.new_cmt_form_block.classList.add("hidden");
                this.task_status_node.textContent = "Выполнена";
                this.task_status_node.classList.remove("text-amber-500");
                this.task_status_node.classList.add("text-green-500");
                this.task_btn_block.removeChild(this.complete_task_btn);
            }
        }
    }
}
