<div class="px-2 py-5 md:!px-10 relative min-h-48">
    @if (count($latestProducts))
        <div id="latestProductsCarousel" class="owl-carousel owl-theme" wire:ignore>
            @foreach ($latestProducts as $product)
                <div class="item">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
        <div class="absolute owl-nav-arrow z-10 bottom-24 hidden md:flex right-12 gap-2 justify-start">
            <div onclick="carouselPrev('latestProductsCarousel')"
                class="p-2 cursor-pointer rounded-full border border-gray-300 flex justify-center items-center gap-8">
                <div class="w-6 h-6 relative transform -rotate-180 transform-origin-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4L10.59 5.41L16.17 11H4V13H16.17L10.59 18.59L12 20L20 12L12 4Z" fill="#A03D42" />
                    </svg>
                </div>
            </div>
            <div onclick="carouselNext('latestProductsCarousel')"
                class="p-2 cursor-pointer rounded-full border border-gray-300 flex justify-center items-center gap-8">
                <div class="w-6 h-6 relative">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4L10.59 5.41L16.17 11H4V13H16.17L10.59 18.59L12 20L20 12L12 4Z" fill="#A03D42" />
                    </svg>
                </div>
            </div>
        </div>
    @endif
</div>
