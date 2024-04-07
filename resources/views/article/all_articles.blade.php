@extends('base')

@section('title')
    Все статьи
@endsection

@section('content')
    <h1>Это страница для просмотра всех статей</h1>
    <table class="table">
        <tr class="table__line">
            <th class="table__header">Name</th>
            <th class="table__header">Short Desc</th>
            <th class="table__header">Date</th>
        </tr>
        @foreach($articles as $article)
            <tr class="table__line">
                <td class="table__cell"><a href="/article/{{$article->id}}">{{ $article->name }}</a></td>
                @if (isset($article->short_desc))
                <td class="table__cell">{{ $article->short_desc }}</td>
                @else
                <td class="table__cell">Нет данных</td>
                @endif
                <td class="table__cell">{{ $article->date }}</td>
            </tr>
        @endforeach
    </table>
    @if ($articles->hasPages())
        <div class="paginator">
            @if ($articles->currentPage() != 1)
                <a href="{{$articles->previousPageUrl()}}" class="button paginator__button">Назад</a>
            @endif
            <ul class="paginator__list">
                @for ($page = 1; $page <= $articles->lastPage(); $page++)
                    <li class="paginator__item">
                        @if ($page == $articles->currentPage())
                            <a href="{{ $articles->url($page) }}" class="paginator__link paginator__link_active" style="color: red">
                                {{ $page }}
                            </a>
                        @else
                            <a href="{{ $articles->url($page) }}" class="paginator__link">
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endfor
            </ul>
            @if ($articles->currentPage() != $articles->lastPage())
                <a href="{{$articles->nextPageUrl()}}" class="button paginator__button">Вперед</a>
            @endif
        </div>
    @endif
@endsection