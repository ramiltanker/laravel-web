@extends('base')

@section('title')
Статьи
@endsection

@section('content')
<h1>Это страница статьи № {{ $article->id }}!</h1>
<h2>{{ $article->name }}</h2>
<img style="margin: 0 auto" src='{{ asset("images/{$article->preview_image}") }}' alt="" width="500px">
<p>{{ $article->desc }}</p>
<p>{{ $article->date }}</p>

@endsection