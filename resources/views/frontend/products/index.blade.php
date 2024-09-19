@extends('layout.frontapp')
@section('title')
    | Products
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('frontend/css/price-range.css') }}" />
@endsection

@section('content')
    <x-bread-crumb :previousLinks="[['link' => route('home'), 'title' => 'Home']]" currentPage="Products" />
    <div class="m-2 md:!m-8 2xl:!m-12">
        <div class="w-full h-full flex flex-col justify-center items-start gap-16 mb-8">
            <div class="h-45 flex flex-col justify-start items-start gap-16">
                <div class="self-stretch h-45 flex flex-col justify-start items-center gap-16">
                    <div class="self-stretch text-[#F29896] text-7xl font-normal leading-tight local-shrimp">
                        Flowers
                    </div>
                </div>
            </div>
        </div>
        @livewire('product.index', 
                [
                    'categories' => $categories, 
                    'shops' => $shops, 
                    'colors' => $colors, 
                    'minPriceValue' => $priceHeight->min_price?:0, 
                    'maxPriceValue' => $priceHeight->max_price?:0,
                    'minHeightValue' => $priceHeight->min_height?:0, 
                    'maxHeightValue' => $priceHeight->max_height?:0,
                    'countries' => $countries,
                ]
        )
    </div>
@endsection

@section('script')
    <script src="{{ asset('frontend/js/product-page.js') }}"></script>
@endsection
