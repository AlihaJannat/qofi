@extends('layout.frontapp')
@section('title')
    | Shop by Vendor
@endsection

@section('content')
    <x-bread-crumb :previousLinks="[
        ['link' => route('home'), 'title' => 'Home'],
    ]" currentPage="Vendors" />
    <div class="m-2 md:!m-8 2xl:!m-12">
        <x-page-header heading="SHOP BY VENDOR" />
        <div>
            @livewire('shop-by-vendor')
        </div>
    </div>
@endsection

