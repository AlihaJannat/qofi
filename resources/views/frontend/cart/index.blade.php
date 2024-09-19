@extends('layout.frontapp')
@section('title')
    | Cart
@endsection

@section('content')
    <x-bread-crumb :previousLinks="[['link' => route('home'), 'title' => 'Home']]" currentPage="Cart" />
    <div class="m-2 md:!m-8 2xl:!m-12">
        <x-page-header heading="Cart" />
    </div>
@endsection
