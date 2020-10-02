@extends('template/container', ['show' => true])

@section('title')
    Home
@endsection

@section('section')
    @include('template/banner')

    @include('template/service')

    @include('template/gallery')
@endsection