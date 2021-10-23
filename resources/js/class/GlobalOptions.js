/**
 * Class for global parameters
 */
export default class GlobalOptions {

    constructor() {
        this.ajaxToken = document.querySelector("meta[name='csrf-token']").content;
    }
}
