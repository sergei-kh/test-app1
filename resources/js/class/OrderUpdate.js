import {send} from "../helper/ajax";

export default class OrderUpdate {

    constructor(formOrder, modal) {
        this.buttons = document.querySelectorAll('.order-edit');
        this.formOrder = formOrder;
        this.modal = modal;
        this.caption = document.getElementById('modal_create_order_label');
    }

    init() {
        this.buttons.forEach((btn) => {
            btn.addEventListener('click', this.onClick.bind(this));
        });
    }

    onClick(e) {
        let btn = e.target;
        let id = btn.dataset.id;
        btn.disabled = true;
        send('get', `order/${id}`, null, this.callbackAfterSend.bind(this), btn);
    }

    callbackAfterSend(response, status, btn) {
        let result = JSON.parse(response);
        btn.disabled = false;
        if (status === 200 && result.status) {
            this.formOrder.reset();
            this.formOrder.enableFormUpdate(btn.dataset.id);
            this.formOrder.fillFields(result.order);
            this.caption.textContent = `Редактирование - заказ № ${btn.dataset.id}`;
            this.modal.show();
        }
    }
}
