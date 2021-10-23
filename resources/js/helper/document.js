/**
 * From string to DOM
 * @param str
 * @returns {Object}
 */
export function strToDom(str) {
    return new DOMParser().parseFromString(str, "text/html").body.firstChild;
}

/**
 * Remove all children
 * @param element
 */
export function clearAll(element) {
    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
}
