import {strToDom} from "../../helper/document";

export default class ProductListLayout {

    static getItems(data) {
        let output = [];
        data.forEach((datum) => {
            let check = document.getElementById(`product_${datum.id}`);
            if (check === null) {
                let item = ProductListLayout.getLayout(datum);
                if (datum.stock !== undefined) {
                    item.children[2].textContent = `Остаток на складе: ${datum.stock}`;
                }
                if (datum.count !== undefined && datum.discount !== undefined) {
                    let inputCount = item.querySelector(`#count_input_${datum.id}`);
                    let inputDiscount = item.querySelector(`#discount_input_${datum.id}`);
                    inputCount.value = datum.count;
                    inputCount.dataset.max = datum.max_count;
                    inputDiscount.value = datum.discount;
                }
                if (datum.cost !== undefined) {
                    item.children[1].textContent = `${datum.cost} руб.`;
                }
                output.push(item);
            }
        });
        return output;
    }

    static getLayout(data) {
        let tpl = `
            <div class="list-group-item product-item position-relative"
                id="product_${data.id}" data-id="${data.id}">
                <span class="product-item__title">${data.name}</span>
                <span class="product-item__price ms-2">${data.price} руб.</span>
                <span class="product-item__count mt-2">Остаток на складе: ${data.qty}</span>
                <div class="d-flex align-items-center mt-2">
                    <div class="d-flex align-items-center">
                        <label for="count_input_${data.id}" class="product-item__small">кол-во:</label>
                        <input type="number"
                        class="form-control form-control-sm product-item__input"
                        id="count_input_${data.id}"
                        value="1"
                        data-max="${data.qty}">
                    </div>
                    <div class="d-flex align-items-center ms-2">
                        <label for="discount_input_${data.id}" class="product-item__small">скидка (%):</label>
                        <input type="number"
                        data-float="true"
                        class="form-control form-control-sm product-item__input"
                        id="discount_input_${data.id}"
                        value="0">
                    </div>
                </div>
                <button type="button" class="btn-close position-absolute product-item__close"></button>
            </div>`;
        return strToDom(tpl);
    }
}
