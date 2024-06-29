/**адрес вебсокета*/
const WEBSOCKET_ADDRESS = document.querySelector("meta[name='websocket']").content;
const USER_LOGIN = document.querySelector("meta[name='login']").content;
const USER_ROLE = document.querySelector("meta[name='role']").content;
const websocket = new ClientWebsocket(WEBSOCKET_ADDRESS, USER_LOGIN,USER_ROLE);