@extends('layouts/default')

@section('content')

    <h1>Custom View (<code>print_r($breadcrumbs)</code>)</h1>

    <?php Config::set('breadcrumbs.view', '_breadcrumbs/print_r') ?>
    @include('_samples')

@stop
