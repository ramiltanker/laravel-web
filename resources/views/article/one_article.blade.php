@extends('base')

@section('title')
Статьи
@endsection

@section('content')
<h1>Это страница статьи № {{ $article->id }}!</h1>

<section class="article-section">
    <div class="article-card">
        <h2>{{ $article->name }}</h2>
        <img src='{{ asset("images/{$article->preview_image}") }}' alt="" height="300px">
        <p>{{ $article->desc }}</p>
        <p>{{ $article->date }}</p>

        @can('update')
            <div class="button-box">
                <a href="/article/{{$article->id}}/edit" class="button button_blue">Редактировать</a>
                <form class="form-for-button" action="/article/{{ $article->id }}" method="POST"> 
                    @csrf
                    @method('DELETE')
                    <button class="button button_red" type="submit">Удалить</button>
                </form>
            </div>
        @endcan
    </div>
</section>
<section class="comment-section island">
    <h2>Комментарии к статье</h2>
    @if (Auth::check() & isset($_GET['res']))
        @if ($_GET['res'] == 1)
            <div class="alert">
                <p class="alert__text">
                    Ваш комментарий успешно сохранен и отправлен на модерацию.
                </p>
            </div>
        @endif
    @endif
    @if (Auth::check())
    <form class="form" action="/comment/store" method="POST">
        @csrf
        <fieldset class="form__fieldset">
            <legend>Создание комментария</legend>
            
            <label class="form__label" for="title">Заголовок</label>
            <input class="form__input" type="text" name="title" id="title" required>

            <label class="form__label" for="text">Текст</label>
            <textarea class="form__input form__textarea" name="text" id="text" required></textarea>

            <input type="hidden" name="article_id" value="{{ $article->id }}">
            <button class="button button_blue" type="submit">Отправить</button>
        </fieldset>
    </form>
    @endif
    <div class="comments-container">
        @foreach ($comments as $comment)
            @if ($comment->status)
                <div class="comment">
                    <p class="comment__title">{{ $comment->title }}</p>
                    <p class="comment__text">{{ $comment->text }}</p>
                    <p class="comment__date">{{ $comment->created_at }}</p>
                    <div class="comment__underline">
                        <p class="comment__author">{{ $comment->getAuthorName() }}</p>
                        @can('comment', $comment)
                            <div class="button-box">
                                <a href="/comment/edit/{{ $comment->id }}" class="button button_blue">Редактировать</a>
                                <a href="/comment/delete/{{ $comment->id }}" class="button button_red">Удалить</a>
                            </div>
                        @endcan
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @if ($comments->hasPages())
        <div class="paginator">
            @if ($comments->currentPage() != 1)
                <a href="{{$comments->previousPageUrl()}}" class="button paginator__button">Назад</a>
            @endif
            <ul class="paginator__list">
                @for ($page = 1; $page <= $comments->lastPage(); $page++)
                    <li class="paginator__item">
                        @if ($page == $comments->currentPage())
                            <a href="{{ $comments->url($page) }}" class="paginator__link paginator__link_active" style="color: red">
                                {{ $page }}
                            </a>
                        @else
                            <a href="{{ $comments->url($page) }}" class="paginator__link">
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endfor
            </ul>
            @if ($comments->currentPage() != $comments->lastPage())
                <a href="{{$comments->nextPageUrl()}}" class="button paginator__button">Вперед</a>
            @endif
        </div>
    @endif
</section>
@endsection 