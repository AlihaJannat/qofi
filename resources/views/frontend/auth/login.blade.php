@extends('layout.frontapp')
@section('title')
    Login
@endsection
@section('content')
    @livewire('auth.login')
@endsection
@section('script')
    @include('frontend.auth.passwordToggle')
@endsection

