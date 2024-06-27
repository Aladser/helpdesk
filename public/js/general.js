const USER_STATUS_NODE = document.querySelector("#user-status");
const USER_NAME_NODE = document.querySelector('meta[name="login"]');

// Смена статуса при клике на элементе списка статусов
if (USER_STATUS_NODE) {
    const USER_STATUS_HEADER_NODE = USER_STATUS_NODE.querySelector(
        "#user-status__header"
    );

    let status_dict = {
        "ready": { name: "Готов", class_name: "user-status-ready" },
        "non-ready": { name: "Не готов", class_name: "user-status-non-ready" },
    };

    USER_STATUS_NODE.querySelectorAll(".user-status__item").forEach((elem) => {
        elem.addEventListener("click", function (e) {
            let status = this.getAttribute("value");
            USER_STATUS_HEADER_NODE.textContent = status_dict[status].name;
            USER_STATUS_HEADER_NODE.className = status_dict[status].class_name;
        });
    });
}