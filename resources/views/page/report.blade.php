@extends('layout')
@section('title', 'Отчёты')
@section('caption', 'Отчёты')
@section('content')
    <form method="get" action="{{route('report.index')}}" class="mt-3 row align-items-end">
        <div class="col-md-4">
            <label for="date_completed" class="form-label">Выберите дату</label>
            <input type="date" name="date_completed" value="{{$date}}" class="form-control" id="date_completed">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Сформировать отчёт</button>
        </div>
    </form>
    <div class="mt-4 row">
        <div class="mb-3">
            <h3>Количество выполненных заказов: {{$countOrders}} шт.</h3>
        </div>
        <div class="mb-3">
            <h3>Общая сумма: {{$totalFormat}} руб.</h3>
        </div>
    </div>
@endsection
