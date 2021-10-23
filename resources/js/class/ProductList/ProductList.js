import ProductListLayout from "./ProductListLayout";
import ProductListLayoutCheckbox from "./ProductListLayoutCheckbox";

import {toInt} from "../../helper/number";
import {send} from "../../helper/ajax";
import {clearAll} from "../../helper/document";

/**
 * The class works with a list of products in the 'form' of adding / editing an order
 */
export default class ProductList {
    constructor() {
        this.box = document.getElementById('product_list');
        this.output = document.getElementById('product_output_list');
        this.inputs = [];
    }

    init() {
        if (this.box !== null) {
            let items = this.box.children;
            for (let i = 0; i < items.length; i++) {
                let checkbox = items[i].children[0];
                if (checkbox.dataset.qty < 1) {
                    items[i].classList.add('list-group-item_disabled');
                }
                checkbox.addEventListener('change', this.onChange.bind(this));
                this.inputs.push(checkbox);
            }
        }
    }

    onChange() {
        let selected = this.getSelectedCheckbox();
        let products = ProductListLayout.getItems(selected);
        products.forEach((product) => {
            this.output.appendChild(product);
        });
        this.syncSelectedProduct(selected);
        this.attachEventInput();
    }

    onInputNumber() {
        let value = this.value.replace(/\D/g, '');
        if (value !== '') {
            let qty = toInt(value);
            let maxQty = toInt(this.dataset.max);
            if (maxQty === 0) {
                this.value = 1;
            } else {
                if (qty <= maxQty) {
                    if (qty < 1) {
                        this.value = 1;
                    } else {
                        this.value = qty;
                    }
                } else {
                    this.value = maxQty;
                }
            }
        } else {
            this.value = 1;
        }
    }

    onInputNumberDiscount() {
        if (this.value < 0) {
            this.value = 0;
        } else if (this.value >= 100) {
            this.value = 100;
        }
    }

    onKeydownNumber(e) {
        let key = e.key;
        if (this.dataset.float) {
            if (key === ',' || key === '-' || key === '+' || key === 'e') {
                e.preventDefault();
            }
        } else {
            if (key === '.' || key === ',' || key === '-' || key === '+' || key === 'e') {
                e.preventDefault();
            }
        }
    }

    onClickRemove() {
        let parent = this.parentNode;
        let id = parent.dataset.id;
        parent.remove();
        let input = document.getElementById(`product_checkbox_${id}`);
        if (input !== null) {
            input.checked = false;
        }
    }

    onFocusoutNumber() {
        if (this.value === '') {
            this.value = 0;
        }
    }

    /**
     * Sets event to input fields
     */
    attachEventInput() {
        let items = document.querySelectorAll('.product-item');
        items.forEach((item) => {
            let id = toInt(item.dataset.id);
            let inputCount = document.getElementById(`count_input_${id}`);
            let inputDiscount = document.getElementById(`discount_input_${id}`);
            let btn = item.querySelector('.product-item__close');
            inputCount.addEventListener('input', this.onInputNumber);
            inputDiscount.addEventListener('input', this.onInputNumberDiscount);
            inputDiscount.addEventListener('focusout', this.onFocusoutNumber);
            inputCount.addEventListener('keydown', this.onKeydownNumber);
            inputDiscount.addEventListener('keydown', this.onKeydownNumber);
            btn.addEventListener('click', this.onClickRemove);
        });
    }

    /**
     * Removes unselected products from markup
     * @param selected
     */
    syncSelectedProduct(selected) {
        let items = document.querySelectorAll('.product-item');
        items.forEach((item) => {
            let id = toInt(item.dataset.id);
            let result = selected.findIndex(fn => fn.id === id);
            if (result === -1) {
                item.remove();
            }
        });
    }

    /**
     * Gets an array of selected checkbox
     * @returns {Array}
     */
    getSelectedCheckbox() {
        let output = [];
        this.inputs.forEach((input) => {
            if (input.checked) {
                output.push({
                    id: toInt(input.value),
                    name: input.nextSibling.textContent.trim(),
                    qty: input.dataset.qty,
                    price: input.dataset.price,
                });
            }
        });
        return output;
    }

    /**
     * Returns an array of the selected products
     * @returns {Array}
     */
    getSelectedProducts() {
        let output = [];
        let items = this.output.querySelectorAll('.product-item');
        items.forEach((item) => {
            let id = toInt(item.dataset.id)
            let count = this.output.querySelector(`#count_input_${id}`);
            let discount = this.output.querySelector(`#discount_input_${id}`);
            output.push({
                id: id,
                count: toInt(count.value),
                discount: discount.value,
            });
        });
        return output;
    }

    /**
     * Set products as selected
     * @param products
     */
    setSelectedProducts(products) {
        if (products !== undefined) {
            let items = ProductListLayout.getItems(products);
            items.forEach((item) => {
                this.output.appendChild(item);
            });
            this.attachEventInput();
            products.forEach((product) => {
                let checkbox = this.box.querySelector(`#product_checkbox_${product.id}`);
                if (checkbox !== null) {
                    checkbox.checked = true;
                }
            });
        }
    }

    /**
     * Refresh product list
     */
    updateList() {
        send('get', '/product', null, (response, status) => {
            if (status === 200) {
                let result = JSON.parse(response);
                let items = ProductListLayoutCheckbox.getItems(result.products);
                clearAll(this.box);
                items.forEach((item) => {
                    this.box.appendChild(item);
                });
                this.inputs = [];
                this.init();
            }
        });
    }

    updateListAtUpdate(id) {
        send('get', `/order/product-info/${id}`, null, (response, status) => {
            if (status === 200) {
                let result = JSON.parse(response);
                if (result.status) {
                    let items = ProductListLayoutCheckbox.getItems(result.products);
                    clearAll(this.box);
                    items.forEach((item) => {
                        this.box.appendChild(item);
                    });
                    this.inputs = [];
                    this.init();
                    clearAll(this.output);
                    this.setSelectedProducts(result.products_order);
                }
            }
        });
    }

    /**
     * Clear product list
     */
    reset() {
        clearAll(this.output);
    }
}
