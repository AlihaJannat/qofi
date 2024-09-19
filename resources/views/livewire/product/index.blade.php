<div class="w-full flex flex-col md:flex-row gap-3">

    <button onclick="toggleProductFilters()" class="md:hidden w-full h-full px-4 py-2 rounded-md border border-red-600">
        <div class="w-full h-full px-4 py-1 rounded-md flex items-center justify-center gap-2">
            <div class="w-4 h-4 flex items-center justify-center">
                <img src="{{ asset('frontend/images/icons/filters.svg') }}" alt="Filters">
            </div>
            <div class="text-btn-pink text-base font-bold">
                Filters
            </div>
        </div>
    </button>


    <!-- Left Side (hidden on small screens) -->
    <div id="product-filter-side" class="md:w-1/4 z-10 hidden md:block h-auto bg-site-bg overflow-y-auto" wire:ignore>
        <div class="w-full h-14 py-2 flex items-center md:hidden" onclick="toggleProductFilters()">
            <div class="w-12 h-12 flex items-center justify-center">
                <div class="relative">
                    <img src="{{ asset('frontend/images/icons/close.svg') }}" alt="Close">
                </div>
            </div>
            <div class="text-gray-900 text-base font-bold">
                Filters
            </div>
        </div>
        <div
            class="p-6 mb-6 mx-3 md:!mx-0 bg-slate-100 bg-opacity-50 rounded-xl border border-gray-300 flex flex-col justify-start items-start gap-4">
            <div class="w-full py-2 flex justify-start items-start gap-2.5">
                <div class="flex-1 h-3 text-black-5 text-lg font-semibold">Categories</div>
            </div>
            <div class="w-full h-px flex justify-center items-center">
                <div class="w-full h-px border border-gray-300"></div>
            </div>
            <div class="w-full h-auto flex flex-col justify-start items-start gap-2">
                @foreach ($categories as $category)
                    <div class="w-full h-6 flex flex-col justify-center items-start gap-2">
                        <div class="w-full h-6 flex flex-col justify-start items-start gap-1">
                            <div class="w-full flex justify-start items-center gap-2">
                                <input type="checkbox"  wire:change="reRenderChild" value="{{ $category->id }}"
                                    {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}
                                    wire:model="selectedCategories" id="category-{{ $category->id }}"
                                    class="custom-checkbox">
                                <label for="category-{{ $category->id }}"
                                    class="flex items-center gap-2 cursor-pointer pt-[0.4rem]">
                                    <div class="text-gray-600 text-sm font-normal leading-5 capitalize">
                                        {{ $category->name }} ({{ $category->products_count }})</div>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- price range --}}
        <div class="p-4 mb-6 mx-3 md:!mx-0 bg-slate-100 border border-gray-300 bg-opacity-50 rounded-xl">
            <div class="w-full py-2 flex justify-start items-start gap-2.5">
                <div class="flex-1 h-3 text-black-5 text-lg font-semibold">Price Range</div>
            </div>
            <div class="w-full h-px flex justify-center items-center mt-3">
                <div class="w-full h-px border border-gray-300"></div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="relative">
                    <input id="minPrice" wire:change="reRenderChild" wire:model="minPrice" type="range"
                        min="{{ $minPriceValue }}" max="{{ $maxPriceValue }}" step="1"
                        value="{{ $minPrice }}"
                        class="absolute w-full h-2 bg-transparent appearance-none pointer-events-auto z-20 range-min">
                    <input id="maxPrice" wire:change="reRenderChild" wire:model="maxPrice" type="range"
                        min="{{ $minPriceValue }}" max="{{ $maxPriceValue }}" step="1"
                        value="{{ $maxPrice }}"
                        class="absolute w-full h-2 bg-transparent appearance-none pointer-events-auto z-10 range-max">
                    <div id="rangeTrack" class="absolute top-0 left-0 h-2 bg-[#FF8080] rounded-lg z-10"></div>
                </div>
                <div class="flex justify-between items-center text-gray-600 mt-6 px-1">
                    <span id="priceLabel">Price 10 KD - 90 KD</span>
                    <button class=" text-gray-600 ">Filter</button>
                </div>
            </div>
        </div>

        {{-- height range --}}
        <div class="p-4 mb-6 mx-3 md:!mx-0 bg-slate-100 border border-gray-300 bg-opacity-50 rounded-xl">
            <div class="w-full py-2 flex justify-start items-start gap-2.5">
                <div class="flex-1 h-3 text-black-5 text-lg font-semibold">Height</div>
            </div>
            <div class="w-full h-px flex justify-center items-center mt-3">
                <div class="w-full h-px border border-gray-300"></div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="relative">
                    <input id="minHeight" wire:change="reRenderChild" type="range" wire:model.live.debounce.500ms="minHeight" min="{{ $minHeightValue }}"
                        max="{{ $maxHeightValue }}" step="1" value="{{ $minHeight }}"
                        class="absolute w-full h-2 bg-transparent appearance-none pointer-events-auto z-20 range-min">
                    <input id="maxHeight" wire:change="reRenderChild" type="range" wire:model.live.debounce.500ms="maxHeight" min="{{ $minHeightValue }}"
                        max="{{ $maxHeightValue }}" step="1" value="{{ $maxHeight }}"
                        class="absolute w-full h-2 bg-transparent appearance-none pointer-events-auto z-10 range-max">
                    <div id="rangeTrackHeight" class="absolute top-0 left-0 h-2 bg-[#FF8080] rounded-lg z-10"></div>
                </div>
                <div class="flex justify-between items-center text-gray-600 mt-6 px-1">
                    <span id="heightLabel">10 cm - 90 cm</span>
                    <button class=" text-gray-600 ">Filter</button>
                </div>
            </div>
        </div>

        {{-- colors --}}
        <div
            class="p-4 mb-6 mx-3 md:!mx-0 bg-slate-100 bg-opacity-50 rounded-xl border border-gray-300 flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <div class="w-full py-2 flex justify-start items-start gap-2.5">
                    <div class="flex-1 h-3 text-black-5 text-lg font-semibold">Colors</div>
                </div>
                <div class="w-full h-px flex justify-center items-center">
                    <div class="w-full h-px border border-gray-300"></div>
                </div>
                <div class="relative w-full h-10 bg-site-bg mt-2">
                    <div class="absolute top-0 right-2">
                        <div class="rounded-full w-10 h-10 flex items-center justify-center">
                            <img src="{{ asset('frontend/images/icons/search.svg') }}" alt="Search"
                                class="w-6 h-6">
                        </div>
                    </div>
                    <input type="text" id="colorSearch"
                        class="absolute inset-0 w-full h-full bg-transparent pl-2 pr-10 text-gray-700 placeholder-gray-400 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-200"
                        placeholder="Search..." onkeyup="filterColors()">
                </div>
            </div>
            <div class="flex gap-2 flex-wrap" id="colorContainer">
                @foreach ($colors as $color)
                    <div class="flex flex-col items-center justify-center w-12 text-center color-swatch cursor-pointer"
                        data-color="{{ $color->name }}">
                        <div class="w-8 h-8 rounded-full mb-1 {{ in_array($color->id, $selectedColors) ? 'active' : '' }} swatch transition-all duration-300" wire:click="toggleColors({{$color->id}})" onclick="toggleSelectColor(this)" style="background-color: {{ $color->hex_code }}">
                        </div>
                        <div class="text-xs text-gray-700 font-bold leading-3 break-words">{{ $color->name }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- countries --}}
        <div
            class="p-6 mb-6 mx-3 md:!mx-0 bg-slate-100 bg-opacity-50 rounded-xl border border-gray-300 flex flex-col justify-start items-start gap-4">
            <div class="w-full py-2 flex justify-start items-start gap-2.5">
                <div class="flex-1 h-3 text-black-5 text-lg font-semibold">Countries</div>
            </div>
            <div class="w-full h-px flex justify-center items-center">
                <div class="w-full h-px border border-gray-300"></div>
            </div>
            <div class="relative w-full h-10 bg-site-bg">
                <div class="absolute top-0 right-2">
                    <div class="rounded-full w-10 h-10 flex items-center justify-center">
                        <img src="{{ asset('frontend/images/icons/search.svg') }}" alt="Search"
                            class="w-6 h-6">
                    </div>
                </div>
                <input type="text" id="countrySearch"
                    class="absolute inset-0 w-full h-full bg-transparent pl-2 pr-10 text-gray-700 placeholder-gray-400 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-pink-200"
                    placeholder="Search..." onkeyup="filterCountries()">
            </div>
            <div class="w-full h-32 overflow-x-auto flex flex-col justify-start items-start gap-2">
                @foreach ($countries as $country)
                    <div class="w-full h-6 flex flex-col justify-center items-start gap-2 country-div" data-country="{{$country->name}}">
                        <div class="w-full h-6 flex flex-col justify-start items-start gap-1">
                            <div class="w-full flex justify-start items-center gap-2">
                                <input wire:click="reRenderChild" type="checkbox" value="{{ $country->id }}"
                                    {{ in_array($country->id, $selectedCountries) ? 'selected' : '' }}
                                    wire:model="selectedCountries" id="country-{{ $country->id }}"
                                    class="custom-checkbox">
                                <label for="country-{{ $country->id }}"
                                    class="flex items-center gap-2 cursor-pointer pt-[0.4rem]">
                                    <div class="text-gray-600 text-sm font-normal leading-5 capitalize">
                                        {{ $country->name }} </div>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- shops --}}
        <div
            class="p-6 mb-6 mx-3 md:!mx-0 bg-slate-100 bg-opacity-50 rounded-xl border border-gray-300 flex flex-col justify-start items-start gap-4">
            <div class="w-full py-2 flex justify-start items-start gap-2.5">
                <div class="flex-1 h-3 text-black-5 text-lg font-semibold">Shops</div>
            </div>
            <div class="w-full h-px flex justify-center items-center">
                <div class="w-full h-px border border-gray-300"></div>
            </div>
            <div class="w-full h-auto flex flex-col justify-start items-start gap-2">
                @foreach ($shops as $shop)
                    <div class="w-full h-6 flex flex-col justify-center items-start gap-2">
                        <div class="w-full h-6 flex flex-col justify-start items-start gap-1">
                            <div class="w-full flex justify-start items-center gap-2">
                                <input wire:click="reRenderChild" type="checkbox" value="{{ $shop->id }}"
                                    {{ in_array($shop->id, $selectedShops) ? 'selected' : '' }}
                                    wire:model="selectedShops" id="shop-{{ $shop->id }}"
                                    class="custom-checkbox">
                                <label for="shop-{{ $shop->id }}"
                                    class="flex items-center gap-2 cursor-pointer pt-[0.4rem]">
                                    <div class="text-gray-600 text-sm font-normal leading-5 capitalize">
                                        {{ $shop->name }} </div>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Right Side (four times wider than left side) -->
    <div class="w-full md:w-3/4">
        <!-- Content for the right side -->
        <div
            class="p-2 mb-6 bg-slate-100 bg-opacity-50 rounded-md border border-gray-300 flex flex-col justify-start items-start gap-4">
            <div class="w-full flex justify-start items-center gap-2.5">
                <div class="flex-1 text-black-5 text-lg font-semibold"></div>
                <x-select-field id="sortOrderPrice" name="sortOrderPrice" wire:change="reRenderChild" wire:model="sortOrderPrice"
                    className="border bg-transparent rounded-sm" label="" :showLabel="false">
                    <option value="">Default Sorting</option>
                    <option value="asc" {{ $sortOrderPrice == 'asc' ? 'selected' : '' }}>Price Asc</option>
                    <option value="desc" {{ $sortOrderPrice == 'desc' ? 'selected' : '' }}>Price Desc</option>
                </x-select-field>
            </div>
        </div>
        <livewire:product.listing :selectedCategories="$selectedCategories" :selectedCountries="$selectedCountries" :selectedShops="$selectedShops" :selectedColors="$selectedColors" :minPrice="$minPrice ?: 0" :maxPrice="$maxPrice ?: 0"
            :minHeight="$minHeight ?: 0" :maxHeight="$maxHeight ?: 0" :sortOrderPrice="$sortOrderPrice" :key="$childComponentKey" />
    </div>
</div>
