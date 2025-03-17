@extends('layout.app')

@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

@section('content_body')
    <p>Welcome to this beautiful admin panel</p>
@stop

@push('css')
@endpush

@push('js')
    <script>console.log("Hi, I'm using the laravel-adminlte pacakge!");</script>
@endpush
