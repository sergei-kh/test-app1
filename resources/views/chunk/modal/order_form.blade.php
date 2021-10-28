<div class="modal fade" id="modal_create_order" tabindex="-1"
     aria-labelledby="modal_create_order_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_create_order_label">Новый заказ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form method="post" action="{{route('order.store')}}" id="order_form">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer" class="form-label">Имя клиента</label>
                        <input type="text" name="customer" class="form-control" id="customer" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="tel" name="phone" class="form-control user_phone" id="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Менеджер</label>
                        <select name="user_id" class="form-select" id="user_id">
                            @foreach($users as $user)
                                @if ($loop->first)
                                    <option selected value="{{$user->id}}">{{$user->name}}</option>
                                    @else
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Тип</label>
                        <select name="type" class="form-select" id="type">
                            <option value="online" selected>online</option>
                            <option value="offline">offline</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Статус</label>
                        <select name="status" class="form-select" id="status">
                            <option value="active" selected>active</option>
                            <option value="completed">completed</option>
                            <option value="canceled">canceled</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p>Выберите товары из списка</p>
                            <div class="list-group product-list position-relative" id="product_list">
                                @foreach($products as $product)
                                    <label class="list-group-item">
                                        <input type="checkbox" class="form-check-input me-1"
                                               id="product_checkbox_{{$product->id}}"
                                               data-price="{{$product->price}}" data-qty="{{$product->stock}}"
                                               value="{{$product->id}}">
                                        {{$product->name}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-7">
                            <p>Выбранные товары:</p>
                            <div class="list-group product-list" id="product_output_list"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <span>Сохранить заказ</span>
                        <span class="d-none spinner-border spinner-border_small text-light" role="status">
                            <span class="visually-hidden">Подождите...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
