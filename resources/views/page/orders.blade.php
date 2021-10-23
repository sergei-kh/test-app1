@extends('layout')
@section('title', 'Заказы')
@section('caption', 'Список заказов')
@section('content')
    <div class="mt-3">
        <button type="button" class="btn btn-outline-primary" id="open_order_form">Создать новый заказ</button>
    </div>
    @forelse ($orders as $order)
        <div class="list-group mt-3">
            <div class="list-group-item list-group-item-action">
                <h5>Заказ № {{$order->id}}</h5>
                <p class="mb-1">Дата создания: {{$order->created_at->format("d.m.Y - H:i")}}</p>
                <p class="mb-1">Статус: {{$order->status}}</p>
                <button type="button" class="mt-2 btn btn-outline-primary order-edit"
                        data-id="{{$order->id}}">Редактировать</button>
            </div>
        </div>
    @empty
    <p class="mt-3">Пока что заказов нет...</p>
    @endforelse
    <div class="mt-4">
        {{$orders->links()}}
    </div>
    @include('chunk.modal.order_form')
@endsection
