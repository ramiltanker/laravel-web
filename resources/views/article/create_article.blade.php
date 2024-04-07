@extends('base')

@section('document_title')
    Создание статьи
@endsection

@section('content')
    <h1>Добро пожаловать на страницу создания статьи</h1>
    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li class="error-list__item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form class="form" action="/article" method="POST">
        @csrf
        <fieldset class="form__fieldset">
            <legend>Создание новой статьи</legend>
            
            <label class="form__label" for="name">Название статьи</label>
            <input class="form__input" type="text" id="name" name="name" required>

            <label class="form__label" for="short_desc">Короткое описание</label>
            <input class="form__input" type="text" id="short_desc" name="short_desc" required>

            <label class="form__label" for="desc">Полное описание</label>
            <textarea class="form__input" type="text" id="desc" name="desc" required></textarea>

            <label class="form__label" for="date">Дата публикации</label>
            <input class="form__input" type="date" id="date" name="date" required>

        </fieldset>
        <button class="form__button button" type="submit">Отправить</button>
    </form>
@endsection