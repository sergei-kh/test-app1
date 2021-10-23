import {send} from "../../helper/ajax";
import {showLoaderBtn, hideLoaderBtn} from "../../helper/form";
import vMasker from "vanilla-masker";

/**
 * Class for order form
 */
export default class FormOrder {

    constructor(productList) {
        this.form = document.getElementById('order_form');
        if (this.form !== null) {
            this.btn = this.form.querySelector('.btn-primary');
            this.productList = productList;
            this.method = 'post';
        }
    }

    init() {
        if (this.form !== null) {
            this.form.addEventListener('submit', this.onSubmit.bind(this));
        }
    }

    onSubmit(e) {
        e.preventDefault();
        let fd = new FormData(this.form);
        let products = this.productList.getSelectedProducts();
        if (products.length) {
            this.btn.disabled = true;
            showLoaderBtn(this.btn);
            fd.append('products', JSON.stringify(products));
            if (this.method === 'put') {
                fd.append('_method', 'PUT');
            }
            send('post', this.form.action, fd, this.callbackAfterSend.bind(this));
        } else {
            alert('Необходимо выбрать товары');
        }
    }

    /**
     * The function that is executed after submitting the form to the server
     * @param response
     * @param status
     */
    callbackAfterSend(response, status) {
        hideLoaderBtn(this.btn);
        this.btn.disabled = false;
        let result = JSON.parse(response);
        if (status === 200) {
            if (result.status) {
                if (this.method !== 'put') {
                    this.form.reset();
                    this.productList.reset();
                    this.productList.updateList();
                } else if (this.method === 'put') {
                    this.productList.updateListAtUpdate(result.id);
                }
            }
        } else if (status === 422) {
            this.handleProductError(result.stock, 'count_input');
            this.handleProductError(result.cost, 'discount_input');
        } else if (status === 500) {
            alert('Упс... произошла ошибка на сервере !');
        }
    }

    /**
     * Highlights the field in which the quantity of goods is indicated more than in the remainder
     * @param errors
     * @param idStr
     */
    handleProductError(errors, idStr) {
        if (errors !== undefined) {
            errors.forEach((error) => {
                let input = document.getElementById(`${idStr}_${error.id}`);
                if (input !== null) {
                    input.classList.add('is-invalid');
                    input.dataset.max = error.max_stock;
                }
            });
        }
    }

    /**
     * Fills in the fields in the form
     * @param data
     */
    fillFields(data) {
        for (let key in data) {
            let input = this.form.querySelector(`#${key}`);
            if (input !== null) {
                let value = data[key];
                if (key === 'phone') {
                    input.value = vMasker.toPattern(value, '+9 (999) 999-99-99');
                } else {
                    input.value = value;
                }
            }
        }
        this.productList.setSelectedProducts(data.products);
    }

    /**
     * Enable update form
     * @param id
     */
    enableFormUpdate(id) {
        this.form.action = `/order/${id}`;
        this.method = 'put';
    }

    /**
     * Enable create form
     */
    enableFormCreate() {
        this.form.action = '/order';
        this.method = 'post';
    }

    /**
     * Reset form
     */
    reset() {
        this.form.reset();
        this.productList.reset();
    }
}
