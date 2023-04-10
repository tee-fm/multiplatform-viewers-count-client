const WebSocket = require('ws');

var myArgs = process.argv.slice(2);
const socket = myArgs[0];
console.log(socket);

const ws = new WebSocket(socket);
ws.onopen = () => {
    const $timeout = setTimeout(() => {
        console.log('force close');
        ws.close()
    }, 30000);

    ws.onmessage = (ev) => {
        const json = JSON.parse(ev.data);
        console.log(json.data.connected);
        clearTimeout($timeout);
        ws.close();
    };
};
