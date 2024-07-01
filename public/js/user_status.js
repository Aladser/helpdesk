const USER_STATUS_NODE = document.querySelector("#user-status");
const USER_NAME_NODE = document.querySelector('meta[name="login"]');
// статусы пользователей
let status_dict = {
    ready: { name: "Готов", class_name: "user-status-ready", value:1 },
    process: { name: "В процессе", class_name: "user-status-process", value:2 },
    non_ready: { name: "Не готов", class_name: "user-status-non-ready", value:3 },
};
let status_key = 'non_ready';


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
    let session_user_login = window.sessionStorage.getItem('user_login');
    if(session_user_login) {
        if(session_user_login == USER_NAME_NODE.content) {
            status_key =  window.sessionStorage.getItem('status_key');
        }
    }
    USER_STATUS_HEADER_NODE.textContent =  status_dict[status_key].name;
    USER_STATUS_HEADER_NODE.className = status_dict[status_key].class_name;
    sendUserStatusToServer(status_key, USER_NAME_NODE.content);

    USER_STATUS_NODE.querySelectorAll(".user-status__item").forEach((elem) => {
        elem.addEventListener("click", function () {
            // клик на статусе в выпадающем списке
            let status_key = this.getAttribute("value");
            USER_STATUS_HEADER_NODE.textContent = status_dict[status_key].name;
            USER_STATUS_HEADER_NODE.className = status_dict[status_key].class_name;
            window.sessionStorage.setItem('user_login', USER_NAME_NODE.content);
            window.sessionStorage.setItem('status_key', status_key);
            sendUserStatusToServer(status_key, USER_NAME_NODE.content);
        });
    });
}


/** Отправить статус пользователя на сервер
 * @param {*} status_key - статус пользователя
 * @param {*} user_login - логин пользователя
 */
function sendUserStatusToServer(status_key, user_login) {
    if(status_websocket) {
        status_websocket.sendData({type:'user-status', login:user_login, status:status_key});
    } else {
        websocket.sendData({type:'user-status', login:user_login, status:status_key});
    }
}; 


