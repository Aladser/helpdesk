/**Клиентский обработчик обновления статуса задачи*/
class UpdateTaskStatusHandler {
    /**
     * @param {*} task_node узел задачи
     * @param {*} new_comment_form_block_node  узел блока отправки комментария
     * @param {*} comment_list_node  блок комментариев
     * @param {*} csrf_token CSRF-токен
     */
    constructor(
        task_node,
        new_comment_form_block_node,
        comment_list_node,
        csrf_token
    ) {
        this.task_node = task_node;
        this.task_status_node = this.task_node.querySelector("#task__status");

        this.task_btn_block = this.task_node.querySelector("#task__btn-block");
        this.take_task_btn = this.task_node.querySelector("#btn-take-task");
        this.complete_task_btn =
            this.task_node.querySelector("#btn-complete-task");
        this.reassign_task_btn =
            this.task_node.querySelector("#btn-reassign-task");

        this.new_cmt_form_block_node = new_comment_form_block_node;
        this.task_id_node =
            this.new_cmt_form_block_node.querySelector("#task__id");
        this.comment_list_node = comment_list_node;
        this.csrf_toke_node = csrf_token;
        this.report_form = this.task_node.querySelector(
            "#report-form-complete-task"
        );

        if (this.report_form) {
            // событие отправки формы отчета о выполнении задачи
            this.report_form.onsubmit = (e) => this.sendCompleteTaskReport(e);
            //отмена отправки ответа
            this.report_form.querySelector(
                "#report-form-complete-task__cancel_btn"
            ).onclick = () => this.cancelShowCompleteTaskForm();
        }

        //взять в работу
        if (this.take_task_btn) {
            this.take_task_btn.addEventListener("click", () =>
                this.send(this.task_id_node.value, "take-task")
            );
        } else {
            //выполнить работу
            if (this.complete_task_btn) {
                this.complete_task_btn.addEventListener("click", () =>
                    this.showCompleteTaskForm()
                );
            }
        }
    }

    /**завершить задачу*/
    showCompleteTaskForm() {
        this.reassign_task_btn.classList.add("hidden");
        this.report_form.classList.remove("hidden");
        this.complete_task_btn.disabled = true;
    }

    /**отмена отправки отчета о выполненнии задачи*/
    cancelShowCompleteTaskForm() {
        this.reassign_task_btn.classList.remove("hidden");
        this.report_form.classList.add("hidden");
        this.report_form.content.value = "";
        this.complete_task_btn.disabled = false;
    }

    /**отправить отчет о завершении задачи*/
    sendCompleteTaskReport(e) {
        e.preventDefault();
        this.send(
            this.task_id_node.value,
            "complete-task",
            this.report_form.content.value
        );
    }

    /**Обновить статус задачи*/
    send(task_id, action, content = null) {
        let headers = {
            "X-CSRF-TOKEN": this.csrf_toke_node.getAttribute("content"),
            "Content-Type": "application/json",
        };

        ServerRequest.execute({
            URL: `/task/${task_id}`,
            processFunc: (data) => this.handle(data),
            method: "put",
            data: JSON.stringify({ action: action, content: content }),
            headers: headers,
        });
    }

    //**обработать ответ сервера на Обновить статус задачи*/
    handle(response) {
        let responseData = JSON.parse(response);

        if (responseData.is_updated === 1) {
            if (responseData["action"] == "take-task") {
                // взять задачу в работу

                this.new_cmt_form_block_node.classList.remove("hidden");
                this.reassign_task_btn.textContent = "Переназначить";
                // исполнитель
                let executor_node = document.createElement("p");
                executor_node.id = "task__executor";
                executor_node.className = "mb-2";
                executor_node.textContent = `Исполнитель: ${responseData.executor}`;
                this.task_node.append(executor_node);

                // статус
                this.task_status_node.textContent = "В работе";
                this.task_status_node.classList.remove("text-rose-600");
                this.task_status_node.classList.add("text-amber-500");

                // кнопка Выполнить вместо Взять в работу
                this.complete_task_btn = document.createElement("button");
                this.complete_task_btn.id = "btn-complete-task";
                this.complete_task_btn.className =
                    "button-theme w-1/6 mb-2 me-2";
                this.complete_task_btn.textContent = "Выполнить";
                this.complete_task_btn.onclick = () =>
                    this.showCompleteTaskForm();
                this.task_btn_block.removeChild(this.take_task_btn);
                this.task_btn_block.prepend(this.complete_task_btn);

                // форма отправки отчета
                this.report_form = document.createElement("form");
                this.report_form.id = "report-form-complete-task";
                this.report_form.className = "hidden";
                this.report_form.innerHTML = `
                    <div class="w-1/2">
                        <h3 class="font-semibold">Отчет о работе:</h3>
                        <textarea class="block-submit__textarea" rows="2" name="content" required=""></textarea>
                        <input type="submit" class="button-theme">
                        <button type='button' id='report-form-complete-task__cancel_btn' class="button-theme w-1/5">Отмена</button>
                    </div>
                `;
                this.report_form.onsubmit = (e) =>
                    this.sendCompleteTaskReport(e);
                this.report_form.querySelector(
                    "#report-form-complete-task__cancel_btn"
                ).onclick = () => this.cancelShowCompleteTaskForm();
                this.task_btn_block.append(this.report_form);
            } else if (responseData["action"] == "complete-task") {
                // статус
                this.task_status_node.textContent = "Выполнена";
                this.task_status_node.classList.remove("text-amber-500");
                this.task_status_node.classList.add("text-green-500");

                // комментарий
                let comment = document.createElement("div");
                comment.className = "cmt-list-block__comment";
                comment.innerHTML = `
                    <div>
                        <div class='cmt-list-block__author color-lighter-theme'>${responseData.executor_short_full_name}</div>
                        <div class='cmt-list-block__time'>${responseData.task_completed_date}</div>
                    </div>
                    <div>${responseData.task_completed_report}</div>
                `;
                this.comment_list_node.prepend(comment);

                this.task_btn_block.remove();
            }
        }
    }
}
