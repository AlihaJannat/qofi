@extends('layout.frontapp')
@section('title')
    | Home
@endsection
@section('style')
    <style>
        .owl-theme .owl-dots .owl-dot.active span {
            background: #D16567;
            transform: scale(1.5)
        }

        .owl-theme .owl-dots {
            text-align: left;
            padding: 3rem;
        }

        .owl-dots .owl-dot:focus {
            outline: none;
            border: none;
        }
    </style>
@endsection
@section('content')
    <div>
        {{-- banners --}}
        <div class="relative w-full overflow-hidden">
            <div id="bannerSlider" class="flex transition-transform duration-500 ease-in-out">
                @foreach ($banners as $banner)
                    @php
                        $imageUrl = asset('images' . $banner->image_name);
                    @endphp
                    <div class="min-w-full h-[230px] md:h-[550px] flex items-center bg-cover bg-right sm:bg-center text-white"
                        style="background-image: url('{{ $imageUrl }}');">
                        <div class="p-4 !pl-14 w-full">
                            <h4
                                class="mt-2 max-w-[60%] text-[16px] md:text-[60px] break-words text-[#F29896] uppercase local-shrimp">
                                {{ $banner->banner_text }}</h4>
                            @if ($banner->url)
                                <div class="flex flex-col justify-start items-start gap-2 mt-3">
                                    <a href="{{ $banner->url }}"
                                        class="flex items-center gap-2 no-underline hover:no-underline bg-gradient-custom rounded md:px-4 md:py-2">
                                        <div class="flex items-center gap-3">
                                            <div class="text-pink-400 text-xs md:text-sm font-medium font-rubik leading-6">
                                                SHOP NOW
                                            </div>
                                            <div
                                                class="flex items-center justify-center w-2 h-2 md:w-4 md:h-4 text-center text-pink-400 text-sm md:text-lg font-normal">
                                                <i class="fas fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <button id="prev" onclick="moveToPrevSlide()"
                class="absolute top-1/2 left-0 transform -translate-y-1/2 px-4 py-2 focus:ring-0 border-none focus:outline-none bg-opacity-50 text-white hover:bg-opacity-75">
                <img src="{{ asset('frontend/images/icons/left.svg') }}" alt="left">
            </button>
            <button id="next" onclick="moveToNextSlide()"
                class="absolute top-1/2 right-0 transform -translate-y-1/2 px-4 py-2 focus:ring-0 border-none focus:outline-none bg-opacity-50 text-white hover:bg-opacity-75">
                <img src="{{ asset('frontend/images/icons/right.svg') }}" alt="right">
            </button>
        </div>
        {{-- end banners --}}

        <x-section-header topTitle="Our Collection" mainHeading="Latest release"
            desc="Lorem ipsum dolor sit amet, consectetur adipiscing elit." btnText="VIEW COLLECTION" link='#featured' />

        @livewire('home.latest-product-carousel', [], key('latest-product-carousel'), ['lazy' => true])
        {{-- <livewire:home.latest-product-carousel lazy /> --}}

        @if (count($promotionalBanners) == 3)
            <div class="mx-auto p-4">
                <div class="flex flex-col-reverse md:flex-row gap-4">
                    <!-- Large Banner -->
                    <div
                        class="relative h-[400px] md:h-[610px] md:flex-1 bg-gradient-to-t from-black/20 to-black/20 rounded-xl">
                        <img src="{{ asset('images' . $promotionalBanners[0]->image_name) }}" alt="Large Banner"
                            width="660" height="610" class="w-full h-full object-cover rounded-xl">
                        <div class="absolute top-0 left-0 h-full w-full flex flex-col justify-between p-4 text-white">
                            <h2 class="text-lg md:text-4xl w-[60%] md:w-[50%] font-bold mt-4">
                                {{ $promotionalBanners[0]->banner_text }}</h2>
                            @if ($promotionalBanners[0]->url)
                                <a href="{{ $promotionalBanners[0]->url }}"
                                    class="inline-block self-end mb-2 md:mb-4 px-6 py-2 bg-btn-pink hover:bg-btn-h-pink text-white rounded-lg">
                                    <span>Shop Now</span>
                                    <i class="ml-2 fas fa-angle-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Small Banners Container -->
                    <div class="flex flex-col gap-4 md:flex-1">
                        <!-- Small Banner 1 -->
                        <div class="relative h-[198px] md:h-[296px] bg-gradient-to-t from-black/20 to-black/20 rounded-xl">
                            <img src="{{ asset('images' . $promotionalBanners[1]->image_name) }}" alt="Small Banner 1"
                                width="712" height="300" class="w-full h-full object-cover rounded-xl">
                            <div class="absolute bottom-4 flex items-center w-full justify-between text-white">
                                <div class="text-xl md:text-4xl font-bold ml-2 md:!ml-4">
                                    {{ $promotionalBanners[1]->banner_text }}</div>
                                {{-- <div class="text-lg">on selected items</div> --}}
                                @if ($promotionalBanners[1]->url)
                                    <a href="#"
                                        class="inline-block items-center mr-2 md:!mr-4 mt-2 px-4 py-2 bg-btn-pink hover:bg-btn-h-pink text-white rounded-lg">
                                        <span>Shop Now</span>
                                        <i class="ml-2 fas fa-angle-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <!-- Small Banner 2 -->
                        <div class="relative h-[198px] md:h-[296px] bg-gradient-to-t from-black/20 to-black/20 rounded-xl">
                            <img src="{{ asset('images' . $promotionalBanners[2]->image_name) }}" alt="Small Banner 2"
                                width="712" height="300" class="w-full h-full object-cover rounded-xl">
                            <div class="absolute bottom-4 flex items-center w-full justify-between text-white">
                                <div class="text-xl md:text-4xl font-bold ml-2 md:!ml-4">
                                    {{ $promotionalBanners[2]->banner_text }}</div>
                                {{-- <div class="text-lg">on selected items</div> --}}
                                @if ($promotionalBanners[2]->url)
                                    <a href="#"
                                        class="inline-block items-center mr-2 md:!mr-4 mt-2 px-4 py-2 bg-btn-pink hover:bg-btn-h-pink text-white rounded-lg">
                                        <span>Shop Now</span>
                                        <i class="ml-2 fas fa-angle-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <x-section-header topTitle="Our Collection" mainHeading="Featured Products"
            desc="Lorem ipsum dolor sit amet, consectetur adipiscing elit." btnText="VIEW COLLECTION" link='#featured' />

        @livewire('home.featured-product-carousel', [], key('featured-product-carousel'), ['lazy' => true])

    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('initializeLatestProductsCarousel', (event) => {
                setTimeout(() => {
                    initializeLatestProductsCarouselFn()
                }, 200);
            });
            Livewire.on('initializeFeaturedProductsCarousel', (event) => {
                setTimeout(() => {
                    initializeFeaturedProductsCarouselFn()
                }, 200);
            });
        });

        var carouselPrev;
        var carouselNext;
        var moveToPrevSlide;
        var moveToNextSlide;
        var initializeLatestProductsCarouselFn;
        var checkItems;

        $(document).ready(function() {
            initializeLatestProductsCarouselFn = function() {
                console.log('latest-products-carousel-init')
                var $carousel = $("#latestProductsCarousel");

                // Check if the carousel is already initialized
                if ($carousel.data('owl.carousel')) {
                    $carousel.trigger('destroy.owl.carousel');
                }

                $carousel.owlCarousel({
                    items: 2, // Number of items to display in mobile view (2 items per slide)
                    loop: true,
                    dots: true,
                    margin: 20,
                    responsive: {
                        768: {
                            items: 4, // Number of items to display in tablet view (4 items per slide)
                        },
                        992: {
                            items: 4, // Number of items to display in desktop view (4 items per slide)
                        }
                    }
                });
                $("#latestProductsCarousel").removeClass('owl-hidden')

            }
            initializeFeaturedProductsCarouselFn = function() {
                console.log('featured-products-carousel-init')
                var $carouselF = $("#featuredProductsCarousel");

                // Check if the carousel is already initialized
                if ($carouselF.data('owl.carousel')) {
                    $carouselF.trigger('destroy.owl.carousel');
                }

                $carouselF.owlCarousel({
                    items: 2, // Number of items to display in mobile view (2 items per slide)
                    loop: true,
                    dots: true,
                    margin: 20,
                    responsive: {
                        768: {
                            items: 4, // Number of items to display in tablet view (4 items per slide)
                        },
                        992: {
                            items: 4, // Number of items to display in desktop view (4 items per slide)
                        }
                    },
                    // onInitialized: checkItems,
                    // onTranslated: checkItems
                });
                $("#featuredProductsCarousel").removeClass('owl-hidden')

            }

            checkItems = function (event) {
                var totalItems = event.item.count; // Total number of items
                var itemsPerPage = event.page.size; // Items per page

                if (totalItems <= itemsPerPage) {
                    $carouselF.find('.owl-nav, .owl-dots').hide();
                } else {
                    $carouselF.find('.owl-nav, .owl-dots').show();
                }
            }

            carouselPrev = function(carouselId) {
                $("#" + carouselId).trigger('prev.owl.carousel');
            }
            carouselNext = function(carouselId) {
                $("#" + carouselId).trigger('next.owl.carousel');
            }

            // slider banner js
            const bannerSlider = document.getElementById('bannerSlider');
            const slides = bannerSlider.children;
            const totalSlides = slides.length;
            let currentIndex = 0;

            document.getElementById('next').addEventListener('click', () => {
                moveToNextSlide();
            });

            document.getElementById('prev').addEventListener('click', () => {
                moveToPrevSlide();
            });

            moveToNextSlide = function() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateBannerSlider();
            }

            moveToPrevSlide = function() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateBannerSlider();
            }

            function updateBannerSlider() {
                bannerSlider.style.transform = `translateX(-${currentIndex * 100}%)`;
            }

            // Auto slide every 4 second
            setInterval(moveToNextSlide, 4000);
            // slider banner js end
        })
    </script>
@endsection
