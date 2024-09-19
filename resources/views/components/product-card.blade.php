    <div
        class="w-full p-2 md:p-4 bg-[rgba(248,233,233,0.50)] rounded-lg overflow-hidden flex flex-col justify-start items-start gap-4">
        <div class="w-full">

            <div class="w-full h-52 lg:h-80 relative bg-[rgba(255,210,207,0.30)] rounded overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full"></div>
                <a class="hover:no-underline"
                    href="{{ route('product.detail', ['shop' => $product->shop?->slug ?: 'not-found', 'slug' => $product->slug]) }}">
                    <img class="absolute top-0 w-full h-full" src="{{ asset('images' . $product->image_name) }}"
                        alt="Product Image" loading="lazy" />
                </a>
                <div class="absolute top-4 right-4 w-9 h-9">
                    <div
                        class="absolute left-[11.4px] top-[3.2px] w-[15.2px] h-[13.49px] text-[#1A1A1A] text-lg font-light">
                        <i class="far fa-heart"></i>
                    </div>
                    <div
                        class="absolute cursor-pointer inset-0 bg-white rounded-full border flex items-center justify-center">
                        <i class="fa-heart {{ $product->in_wishlist ? 'fa text-btn-pink' : 'far text-black-2' }} text-lg"
                            onclick="addToWishlist({{ $product->id }}, this)"></i>
                    </div>
                </div>
            </div>
            <a class="hover:no-underline"
                href="{{ route('product.detail', ['shop' => $product->shop?->slug ?: 'not-found', 'slug' => $product->slug]) }}">
                <div class="w-full h-18 flex flex-col justify-start items-start gap-2 py-2">
                    <div class="w-full h-4 flex flex-col justify-start items-start gap-4">
                        <div class="w-full text-[#555555] text-sm md:text-xl font-normal leading-4">
                            {{ $product->name }}</div>
                    </div>
                    <div class="w-full h-[13px] flex flex-col justify-start items-start gap-4">
                        <div class="w-full text-[#555555] text-xs md:text-lg font-normal leading-5">By
                            {{ $product->shop?->name }}</div>
                    </div>
                    <div class="w-full flex justify-between items-center mt-3">
                        <div class="text-[#555555] text-sm md:text-2xl font-normal leading-6">KWD
                            {{ number_format($product->defaultHeight?->getPrice(), 2) }}
                        </div>
                        <div class="text-center text-[#555555] text-xs md:text-lg font-normal line-through leading-5">
                            @if ($product->defaultHeight?->getPrice() != $product->defaultHeight?->price)
                                KWD
                                {{ number_format($product->defaultHeight?->price, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
            </a>
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
