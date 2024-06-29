const USER_STATUS_NODE = document.querySelector("#user-status");
const USER_NAME_NODE = document.querySelector('meta[name="login"]');

// вебсокет для страниц без него
let status_websocket = null;
if(typeof websocket == 'undefined') {
    let websocket_address = document.querySelector("meta[name='websocket']").content;
    let user_login = document.querySelector("meta[name='login']").content;
    let user_role = document.querySelector("meta[name='role']").content;
    status_websocket = new ClientWebsocket(websocket_address, user_login, user_role);
}

// ----- СТАТУС ПОЛЬЗОВАТЕЛЯ -----
// если пользователь - исполнитель
if (USER_STATUS_NODE) {
    const USER_STATUS_HEADER_NODE = USER_STATUS_NODE.querySelector("#user-status__header");

    // проверка наличия пользователя в сессии браузера
    let session_user_login = window.sessionStorage.getItem('user-login');
    if(session_user_login) {
        if(session_user_login == USER_NAME_NODE.content) {
            USER_STATUS_HEADER_NODE.textContent =  window.sessionStorage.getItem('user-status');
            USER_STATUS_HEADER_NODE.className = window.sessionStorage.getItem('user_status-classname');
            sendUserStatusToServer(USER_NAME_NODE.content);
        }
    } else {
        USER_STATUS_HEADER_NODE.className = 'user-status-non-ready';
    }

    // установка статуса пользователем
    let status_dict = {
        "ready": { name: "Готов", class_name: "user-status-ready" },
        "non-ready": { name: "Не готов", class_name: "user-status-non-ready" },
    };
    USER_STATUS_NODE.querySelectorAll(".user-status__item").forEach((elem) => {
        elem.addEventListener("click", function () {
            // клик на статусе в выпадающем списке
            let status = this.getAttribute("value");
            USER_STATUS_HEADER_NODE.textContent = status_dict[status].name;
            USER_STATUS_HEADER_NODE.className = status_dict[status].class_name;
            window.sessionStorage.setItem('user-login', USER_NAME_NODE.content);
            window.sessionStorage.setItem('user-status', USER_STATUS_HEADER_NODE.textContent);
            window.sessionStorage.setItem('user_status-classname', USER_STATUS_HEADER_NODE.className);
            sendUserStatusToServer(USER_NAME_NODE.content);
        });
    });
}

/**отправить статус пользователя на сервер*/
function sendUserStatusToServer(user_login) {
    let user_status_header_node = USER_STATUS_NODE.querySelector("#user-status__header");
    let user_status = user_status_header_node ? Number(user_status_header_node.className == 'user-status-ready'):0;

    if(status_websocket) {
        status_websocket.sendData({type:'user-status', login:user_login, status:user_status});
    } else {
        websocket.sendData({type:'user-status', login:user_login, status:user_status});
    }

}; 


