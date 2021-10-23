import vMasker from "vanilla-masker";

export default class MaskInput {

    constructor() {
        this.inputs = document.querySelectorAll('.user_phone')
    }

    init() {
        this.inputs.forEach((input) => {
            vMasker(input).maskPattern('+9 (999) 999-99-99');
            input.addEventListener('focus', function () {
                if(!this.value) {
                    this.value = '+7 ';
                }
            });
        });
    }
}
