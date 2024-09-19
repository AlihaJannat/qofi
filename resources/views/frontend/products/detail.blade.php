@extends('layout.frontapp')
@section('title')
    | {{ $product->name }}
@endsection
@section('content')
    <x-bread-crumb :previousLinks="[
        ['link' => route('home'), 'title' => 'Home'],
        ['link' => route('product.index'), 'title' => 'Products'],
    ]" currentPage="{{ $product->name }}" />
    <div class="m-2 md:!m-8 2xl:!m-12">
        <div class="w-full flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-2/5">
                <div class="w-full mb-2">
                    <img src="{{ asset('images' . $product->image_name) }}" id="product-main-img" alt="Main Image"
                        class="w-full h-auto object-cover aspect-square">
                </div>
                @if (count($product->images))
                    <div class="w-full grid grid-cols-4 gap-2 md:grid-cols-2">
                        @foreach ($product->images as $image)
                            <div class="relative w-full pb-[100%]">
                                <img onclick="showOnMainImg(`{{ asset('images' . $image->image_name) }}`)"
                                    src="{{ asset('images/' . $image->image_name) }}" alt="Image 1"
                                    class="absolute top-0 left-0 w-full h-full object-cover">
                            </div>
                        @endforeach
                        <div class="relative w-full pb-[100%]">
                            <img onclick="showOnMainImg(`{{ asset('images' . $product->image_name) }}`)"
                                src="{{ asset('images' . $product->image_name) }}" alt="Image 1"
                                class="absolute top-0 left-0 w-full h-full object-cover">
                        </div>
                    </div>
                @endif
            </div>
            <div class="w-full md:w-3/5 p-2">
                <div class="flex flex-col justify-start items-start gap-6 w-full h-full">
                    <div class="flex flex-col justify-start items-start gap-2 w-full">
                        <div class="flex flex-col justify-start items-start gap-2 w-full">
                            <div class="text-black-3 text-2xl font-bold leading-[33.6px] break-words w-full capitalize">
                                {{ $product->name }}</div>
                            <div class="flex flex-col justify-start items-start gap-4 w-full">
                                <div
                                    class="text-[#652326] text-base font-normal leading-[12.8px] break-words w-full capitalize">
                                    By
                                    {{ $product->shop?->name }}</div>
                            </div>
                        </div>
                        <div class="flex justify-start items-start gap-2">
                            <div class="text-black-3 text-xl font-bold leading-7 break-words">KWD
                                {{ number_format($product->defaultHeight?->getPrice(), 2) }}</div>
                            <div class="flex justify-center items-center gap-2 w-15 h-full">
                                <div
                                    class="text-center text-[#652326] text-xs font-bold line-through leading-[18px] break-words">
                                    @if ($product->defaultHeight?->getPrice() != $product->defaultHeight?->price)
                                        KWD
                                        {{ number_format($product->defaultHeight?->price, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-start items-start gap-2 w-full h-[42px]">
                        <div class="flex flex-col justify-start items-start gap-4 w-full h-[13px]">
                            <div class="text-black-5 text-base font-normal leading-[12.8px] break-words w-full">Vendor
                                Reviews</div>
                        </div>
                        <div class="flex justify-start items-center gap-2">
                            <div class="flex justify-start items-center gap-1">
                                <i class="fas fa-star text-light-pink"></i>
                                <i class="fas fa-star text-light-pink"></i>
                                <i class="fas fa-star text-light-pink"></i>
                                <i class="fas fa-star-half-alt text-light-pink"></i>
                                <i class="far fa-star text-light-pink"></i>
                            </div>
                            <div class="text-center text-black-5 text-sm font-normal leading-6 break-words">(3.5 stars) • 10
                                reviews</div>
                        </div>
                    </div>
                    <div class="text-black-5 text-base font-normal leading-6 break-words w-full">
                        {{ $product->short_description }}
                    </div>
                    <div class="flex flex-col justify-start items-start gap-6">
                        <div class="flex flex-col justify-start items-start gap-2">
                            <div class="text-black-3 text-base font-normal leading-6 break-words w-full">Variant</div>
                            <div class="flex flex-col justify-start items-start gap-4 w-full">
                                <div class="flex flex-wrap justify-start items-start gap-4">
                                    <div
                                        class="px-4 py-2 cursor-pointer bg-black-5 border border-black flex justify-center items-center gap-2">
                                        <div class="text-white text-base font-normal leading-6 break-words">H 34cm x W 20cm
                                        </div>
                                    </div>
                                    @foreach ($product->heights as $height)
                                        <div
                                            class="px-4 py-2 border-2 cursor-pointer border-black-5 flex justify-center items-center gap-2">
                                            <div class="text-black-5 text-base font-normal leading-6 break-words">H 40cm x W
                                                20cm</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col justify-start items-start gap-2 h-[68px]">
                            <div class="text-black-3 text-base font-normal leading-6 break-words w-full">Quantity</div>
                            <div
                                class="inline-flex justify-start items-start gap-2 p-2 bg-white border border-[#DDDDDD] rounded">
                                <div class="flex justify-center items-center w-5 h-5">
                                    <div class="relative w-5 h-5">
                                        <div
                                            class="absolute border-black-3 border-t-[1.5px] border-b-[1.5px] border-l-[1.5px] w-[10px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        </div>
                                        <div
                                            class="absolute border-black-3 border-l-[1.5px] border-r-[1.5px] border-b-[1.5px] h-[10px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        </div>
                                        <div class="absolute opacity-0 w-5 h-5"></div>
                                    </div>
                                </div>
                                <div class="text-black-5 text-base font-normal break-words">1</div>
                                <div class="flex justify-center items-center w-5 h-5">
                                    <div class="relative w-5 h-5">
                                        <div
                                            class="absolute border-black-3 border-t-[1.5px] border-b-[1.5px] border-l-[1.5px] w-[10px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        </div>
                                        <div class="absolute opacity-0 w-5 h-5"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inline-flex justify-start items-start gap-4 w-full">
                            <div class="flex justify-start items-start gap-2 h-11 flex-1">
                                <x-input-field label="" wire:model="date" id="date" type="date" name="date"
                                    :showLabel="false" />
                                <div class="flex justify-center items-center gap-2 w-11 h-11 p-3 bg-[#BA5053] rounded-lg">
                                    <div class="flex justify-center items-center w-6 h-6">
                                        <div class="relative w-6 h-6">
                                            <div
                                                class="absolute border-white border-t-[1.5px] border-b-[1.5px] border-l-[1.5px] w-5 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            </div>
                                            <div
                                                class="absolute border-white border-t-[1.5px] border-b-[1.5px] border-r-[1.5px] h-[7.67px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            </div>
                                            <div class="absolute opacity-0 w-6 h-6"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start items-start gap-2 h-11 flex-1">
                                <div class="flex justify-start items-center gap-4 w-[230px] h-11 p-4 bg-white rounded-lg">
                                    <div class="flex justify-start items-center gap-2 w-full h-6">
                                        <div class="text-[#B8BBC6] text-base font-normal leading-6 break-words w-full">
                                            Delivery Time</div>
                                    </div>
                                </div>
                                <div class="flex justify-center items-center gap-2 w-11 h-11 p-3 bg-[#BA5053] rounded-lg">
                                    <div class="flex justify-center items-center w-6 h-6">
                                        <div class="relative w-6 h-6">
                                            <div
                                                class="absolute border-white border-t-[1.5px] border-b-[1.5px] border-l-[1.5px] w-5 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            </div>
                                            <div
                                                class="absolute border-white border-t-[1.5px] border-b-[1.5px] border-r-[1.5px] h-[7.67px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            </div>
                                            <div class="absolute opacity-0 w-6 h-6"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col justify-start items-start gap-2 w-full h-[112px]">
                            <div class="text-black-3 text-base font-normal leading-6 break-words w-full">Delivery Method
                            </div>
                            <div class="flex flex-col justify-start items-start gap-4 w-full h-20">
                                <div class="flex flex-col justify-start items-start gap-2 w-full h-[72px]">
                                    <div class="inline-flex justify-start items-start gap-4">
                                        <div
                                            class="flex flex-col justify-start items-start gap-2 w-[70px] h-[72px] p-4 bg-white border border-[#DDDDDD] rounded-lg">
                                            <div class="w-[25.83px] h-[23.17px]"></div>
                                            <div
                                                class="text-black-5 text-base font-normal leading-6 break-words w-[57.17px]">
                                                Pick Up</div>
                                        </div>
                                        <div
                                            class="flex flex-col justify-start items-start gap-2 w-[90px] h-[72px] p-4 bg-[#BA5053] border border-[#BA5053] rounded-lg">
                                            <div class="w-[27.93px] h-[21.07px]"></div>
                                            <div class="text-white text-base font-normal leading-6 break-words w-[57.17px]">
                                                Delivery</div>
                                        </div>
                                        <div
                                            class="flex flex-col justify-start items-start gap-2 w-[85px] h-[72px] p-4 bg-white border border-[#DDDDDD] rounded-lg">
                                            <div class="w-[27.93px] h-[21.07px]"></div>
                                            <div
                                                class="text-black-5 text-base font-normal leading-6 break-words w-[57.17px]">
                                                Courier</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-10 flex flex-col justify-start items-start gap-2">
                            <button
                                class="w-full h-10 px-4 py-2 bg-gradient-to-r from-[#FBEDBC] to-[rgba(251,237,188,0.50)] rounded flex justify-start items-center gap-2 hover:from-[#FBE0A1] hover:to-[#FBE0A1]">
                                <div class="flex-1 h-4 flex justify-center items-center gap-3">
                                    <div class="text-[#F38181] text-xs md:text-sm font-medium leading-6">ADD TO CART</div>
                                    <div class="w-4 h-4 flex justify-center items-center">
                                        <i class="fas fa-plus text-[#F38181] text-base md:text-lg"></i>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var showOnMainImg;
        $(document).ready(function() {
            showOnMainImg = function (imgUrl) {
                console.log(imgUrl)
                $('#product-main-img').attr('src', imgUrl);
            }
        })
    </script>
@endsection
