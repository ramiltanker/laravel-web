<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    @vite([ 'resources/js/app.js', 
            'resources/css/normalize.css', 
            'resources/css/app.css', 
            'resources/css/dropdown.css',
            'resources/css/form.css',
            'resources/css/tables.css',
            'resources/css/pagination.css',
            'resources/css/comment.css'])
</head>
<body>
    <header class="header">
        <nav class="header__nav">
            <a href="/" class="header__link @activeLink('/')">Главная</a>
            <a href="/article" class="header__link @activeLink('article')">Статьи</a>
            @can('create')
                <a href="/article/create" class="header__link @activeLink('article/create')">Создание статьи</a>
            @endcan
            @can('comment-admin')
                <a href="/comment" class="header__link @activeLink('comment')">Все комментарии</a>
            @endcan
        </nav>
        @auth
            <div class="dropdown">
                <button class="dropbtn">Новые комментарии ({{ auth()->user()->unreadNotifications->count() }})</button>
                <div class="dropdown-content">
                    @foreach (auth()->user()->unreadNotifications as $notify)
                        <a href="{{ route('article.show', ['article' => $notify->data['article']['id'], 'notify' => $notify->id]) }}" class="header__link">
                            Статья: {{ $notify->data['article']['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endauth
        @if (Auth::user() != null)
        <a href="/logout" class="button button_red">{{ Auth::user()->name }}</a>
        @else
            <div class="button-box">
                <a href="/register" class="button button_blue">Регистрация</a>
                <a href="/login" class="button button_green">Вход</a>
            </div>
        @endif
    </header>
    <div id="app">
		<App />
	 </div>   
    <main class="main">
        <div class="container island">
            @yield('content')
        </div>
    </main>
    <footer class="footer">
        <p class="footer__text">Ашрафулин Рамиль - 221-321</p>
    </footer>
</body>
</html>