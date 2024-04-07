@extends('base')

@section('title')
Статьи
@endsection

@section('content')
<h1>Это страница для просмотра всех статей!</h1>
<table class="table">
    <tr class="table__line">
        <th class="table__header">Name</th>
        <th class="table__header">Short Desc</th>
        <th class="table__header">Image</th>
        <th class="table__header">Date</th>
    </tr>
    @foreach($articles as $article)
        <tr class="table__line">
            <td class="table__cell">{{ $article->name }}</td>
            @if (isset($article->short_desc))
            <td class="table__cell">{{ $article->short_desc }}</td>
            @else
            <td class="table__cell">Нет данных</td>
            @endif
            <td class="table__cell"><a href="/one_article/?id={{ $article->id }}"><img src='{{ asset("images/{$article->preview_image}") }}' alt="" height="100px"></a></td>
            <td class="table__cell">{{ $article->date }}</td>
        </tr>
    @endforeach
</table>

@endsection