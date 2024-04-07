@extends('base')

@section('document_title')
    Редактирование статьи
@endsection

@section('content')
    <h1>Добро пожаловать на страницу редактирования комментария к статье</h1>
    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li class="error-list__item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form class="form" action="/comment/update/{{ $comment->id }}" method="POST">
        @csrf
        <fieldset class="form__fieldset">
            <legend>Редактирование комментария</legend>

            <label class="form__label" for="title">Заголовок</label>
            <input class="form__input" type="text" name="title" id="title" value="{{ $comment->title }}" required>

            <label class="form__label" for="text">Текст</label>
            <textarea name="text" id="text" required>{{ $comment->text }}</textarea>

            <button class="button button_blue" type="submit">Отправить</button>

        </fieldset>
        <button class="form__button button" type="submit">Отправить</button>
    </form>
@endsection