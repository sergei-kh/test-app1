export function send(method, src, data, callback, element) {
    let xhr = new XMLHttpRequest;
    xhr.open(method, src);
    xhr.setRequestHeader("X-CSRF-Token", window.globalOptions.ajaxToken);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.send(data);
    xhr.onload = (response) => {
        if (callback !== null) {
            callback(response.currentTarget.response, response.target.status, element);
        }
    };
}
