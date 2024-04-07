@extends('base')

@section('title')
Статьи
@endsection

@section('content')
<h1>Это страница для просмотра контактов!</h1>
<p>Список контактов:</p>
<ul>
    @foreach($data as $contact)
        <li><p>{{ $contact }}</p></li>
    @endforeach
</ul>

@endsection