@extends('base')

@section('title')
    Все комментарии
@endsection

@section('content')
    <h1>Это страница для просмотра всех комментариев</h1>
    <table class="table" style="border: 1px solid black">
        <tr class="table__line">
            <th class="table__header">№</th>
            <th class="table__header">Заголвок</th>
            <th class="table__header">Текст</th>
            <th class="table__header">Статья</th>
            <th class="table__header">Статус</th>
        </tr>
        @foreach($comments as $comment)
            <tr class="table__line">
                <td class="table__cell">
                    {{ $comment->id }}
                </td>
                <td class="table__cell">
                    {{ $comment->title }}
                </td>
                <td class="table__cell">
                    {{ $comment->text }}
                </td>
                <td class="table__cell">
                    <a href="/article/{{ $comment->article_id }}">Статья №{{ $comment->article_id }} </a>
                </td>
                <td class="table__cell">
                    <div class="button-box button-box_vertical">
                        @if ($comment->status === null)
                            <a href="/comment/accept/{{ $comment->id }}?page={{$comments->currentPage()}}" class="button button_green">Принять</a>
                            <a href="/comment/reject/{{ $comment->id }}?page={{$comments->currentPage()}}" class="button button_red">Отклонить</a>
                        @else
                            @if ($comment->status)
                                <a href="/comment/reject/{{ $comment->id }}?page={{$comments->currentPage()}}" class="button button_green">Принят</a>
                            @else
                                <a href="/comment/accept/{{ $comment->id }}?page={{$comments->currentPage()}}" class="button button_red">Отклонен</a>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    
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
@endsection