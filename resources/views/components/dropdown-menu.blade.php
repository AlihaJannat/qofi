<div class="hidden transition-all duration-500 absolute left-0 top-[7.5rem] md:top-[6.5rem] w-full bg-white shadow-lg z-20 item-submenu"
    id="{{ $submenuId }}">
    <div
        class="w-full h-full bg-[#FFE8E7] shadow-md shadow-[#FFD2CF4D] border-b-2 border-[#A03D42] flex flex-col justify-start items-center">
        <div class="w-full flex flex-col lg:flex-row justify-start items-start">
            @if (count($subCategories))
                <div class="flex-1 py-8 px-4 lg:px-16 flex justify-start items-start gap-8">
                    <div class="flex-1 flex flex-col justify-start items-start gap-4">
                        <div class="w-full text-black-5 text-sm font-semibold font-roboto leading-[21px]">
                            Sub Categories for {{ $label }}
                        </div>
                        @foreach ($subCategories as $item)
                            <div class="w-full py-2 flex flex-col gap-3">
                                <div class="flex-1 flex flex-col justify-start items-start">
                                    <div class="w-full text-black-3 text-base font-semibold font-roboto leading-[24px]">
                                        {{ $item->name }}
                                    </div>
                                    {{-- <div class="w-full text-black-5 text-sm font-normal font-roboto leading-[21px]">
                                    Lorem ipsum dolor sit amet consectetur elit</div> --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if (count($products))
                <div
                    class="w-full lg:w-[60%] h-full py-8 px-4 lg:px-8 bg-[#FFF0EF] flex flex-col justify-start items-start gap-4">
                    <div class="w-full text-black-5 text-sm font-semibold font-roboto leading-[21px]">
                        Featured Products</div>
                        <div class="w-full flex flex-col gap-4">
                            @foreach ($products->chunk(2) as $chunk)
                                <div class="flex flex-wrap w-full justify-start items-start gap-4 lg:gap-6">
                                    @foreach ($chunk as $key => $item)
                                        <div class="w-full lg:w-[calc(50%-1rem)] py-2 flex flex-col sm:flex-row justify-start items-start gap-4 lg:gap-6">
                                            <img class="w-full sm:w-[40%] lg:w-[160px] h-auto"
                                                 src="{{asset('images'.$item->image_name)}}" alt="Product Image" />
                                            <div class="flex-1 flex flex-col justify-start items-start gap-2">
                                                <div class="w-full h-auto flex flex-col justify-start items-start gap-1">
                                                    <div class="w-full text-black text-base font-semibold font-roboto leading-[24px]">
                                                        {{ $item->name }}
                                                    </div>
                                                    <div class="w-full text-black-5 text-sm font-normal font-roboto leading-[21px]">
                                                        {{ $item->short_description }}
                                                    </div>
                                                </div>
                                                <div class="text-black-3 text-sm font-normal font-roboto underline leading-[21px]">
                                                    Read more
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    <div class="py-1 flex justify-center items-center gap-2">
                        <a href="{{route('product.index')}}?selectedCategories[0]={{$categoryId}}" class="!text-black-5 text-base font-normal font-roboto leading-[24px]">See
                            all
                        </a>
                        <div class="w-6 h-6 relative">
                            <img src="{{ asset('frontend/images/icons/chevron-right.svg') }}" alt=""
                                srcset="">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
