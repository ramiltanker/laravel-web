@extends('base')

@section('document_title')
    Авторизация
@endsection

@section('content')
    <h1>Добро пожаловать на страницу авторизации!</h1>
    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li class="error-list__item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form class="form form_register" action="/authenticate" method="POST">
        @csrf
        <fieldset class="form__fieldset">
            <legend>Форма авторизации</legend>
            <label class="form__label" for="email">Адрес электронной почты</label>
            <input class="form__input" type="email" id="email" name="email" required>

            <label class="form__label" for="password">Пароль</label>
            <input class="form__input" type="password" id="password" name="password" required>
        </fieldset>
        <button class="form__button button" type="submit">Отправить</button>
    </form>
@endsection
