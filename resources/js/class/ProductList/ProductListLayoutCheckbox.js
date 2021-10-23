import {strToDom} from "../../helper/document";

export default class ProductListLayoutCheckbox {

    static getItems(data) {
        let output = [];
        data.forEach((datum) => {
            let item = ProductListLayoutCheckbox.getLayout(datum);
            output.push(item);
        });
        return output;
    }

    static getLayout(data) {
        let tpl = `
        <label class="list-group-item">
            <input type="checkbox" class="form-check-input me-1"
             id="product_checkbox_${data.id}"
             data-price="${data.price}" data-qty="${data.stock}" value="${data.id}">
             ${data.name}
        </label>`;
        return strToDom(tpl);
    }
}
