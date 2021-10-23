import "bootstrap";
import {Modal} from 'bootstrap'

import ProductList from "./class/ProductList/ProductList";
import FormOrder from "./class/Form/FormOrder";
import GlobalOptions from "./class/GlobalOptions";
import MaskInput from "./class/MaskInput";
import OrderUpdate from "./class/OrderUpdate";
import OrderCreate from "./class/OrderCreate";

window.globalOptions = new GlobalOptions();

let modalEl = document.getElementById('modal_create_order');
let modal = null;
if (modalEl !== null) {
    modal = new Modal(document.getElementById('modal_create_order'), {keyboard: false});
}

const productList = new ProductList();
productList.init();

const formOrder = new FormOrder(productList);
formOrder.init();

new OrderUpdate(formOrder, modal).init();
new OrderCreate(formOrder, modal).init();
new MaskInput().init();
