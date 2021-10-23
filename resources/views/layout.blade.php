<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <meta name="csrf-token" content="{{csrf_token()}}">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <nav class="list-group">
                    <a href="{{route('main')}}"
                       class="list-group-item list-group-item-action @if(Route::is('main')) active @endif">Заказы</a>
                    <a href="{{route('report')}}"
                       class="list-group-item list-group-item-action @if(Route::is('report')) active @endif">Отчёты</a>
                </nav>
            </div>
            <main class="col-md-8">
                <h1>@yield('caption')</h1>
                @yield('content')
            </main>
        </div>
    </div>
    <script src="{{mix('js/app.js')}}"></script>
</body>
</html>
