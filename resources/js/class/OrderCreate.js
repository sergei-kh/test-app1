export default class OrderCreate {

    constructor(formOrder, modal) {
        this.btn = document.getElementById('open_order_form');
        this.formOrder = formOrder;
        this.modal = modal;
        this.caption = document.getElementById('modal_create_order_label');
    }

    init() {
        if (this.btn !== null) {
            this.btn.addEventListener('click',  () => {
                this.formOrder.reset();
                this.formOrder.enableFormCreate();
                this.caption.textContent = 'Новый заказ';
                this.modal.show();
            });
        }
    }
}
