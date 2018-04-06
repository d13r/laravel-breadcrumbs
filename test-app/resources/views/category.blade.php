@extends('layouts/default')

@section('content')

    <h1>{{ $category->title }}</h1>
    <p>Content goes here...</p>

    {{ $paginator->links('pagination::bootstrap-4') }}

@stop
