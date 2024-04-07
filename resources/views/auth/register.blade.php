@extends('base')

@section('document_title')
    Регистрация
@endsection

@section('content')
    <h1>Добро пожаловать на страницу регистрации!</h1>
    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li class="error-list__item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form class="form form_register" action="/create_user" method="POST">
        @csrf
        <fieldset class="form__fieldset">
            <legend>Форма регистрации</legend>
            <label class="form__label" for="name">Имя пользователя</label>
            <input class="form__input" type="text" id="name" name="name" required>

            <label class="form__label" for="email">Адрес электронной почты</label>
            <input class="form__input" type="email" id="email" name="email" required>

            <label class="form__label" for="password">Пароль</label>
            <input class="form__input" type="password" id="password" name="password" required>
            
            <label class="form__label" for="password_repeat">Повторите пароль</label>
            <input class="form__input" type="password" id="password_repeat" name="password_repeat" required>
        </fieldset>
        <button class="form__button button" type="submit">Отправить</button>
    </form>
@endsection