<div>
    <header class="header border-b border-[#A03D42] sm:border-none">
        <div class="flex flex-col">
            <div class="flex justify-between p-2 px-4 items-center">
                <a href="#" class="text-gray-600 hidden sm:flex hover:text-gray-800 items-center">
                    <img src="{{ asset('frontend/images/icons/left.svg') }}" alt="Back to Shopping">
                    <span class="hidden md:block">Back to Shopping</span>
                </a>
                <button id="menu-toggle" onclick="toggleMenu()" class="sm:hidden focus:outline-none">
                    <img src="{{ asset('frontend/images/icons/menu.svg') }}" alt="Menu">
                </button>
                <a href="{{ route('home') }}" class="w-[40vw] sm:w-auto">
                    <img src="{{ asset(app_setting('site_logo', 'frontend/images/logo.svg')) }}" alt="Logo">
                </a>
                <div class="flex space-x-2 sm:space-x-4 text-gray-600">
                    <a href="#" class="w-5 sm:w-auto">
                        <img src="{{ asset('frontend/images/icons/search.svg') }}" alt="Search">
                    </a>
                    <a href="#" class="w-5 sm:w-auto">
                        <img src="{{ asset('frontend/images/icons/user.svg') }}" alt="User">
                    </a>
                    <a href="#" class="w-5 sm:w-auto">
                        <img src="{{ asset('frontend/images/icons/cart.svg') }}" alt="Cart">
                    </a>
                </div>
            </div>
            <nav class="bg-black-3 py-2 hidden sm:flex items-center justify-center font-medium">
                <div class="px-2">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a>
                </div>
                <div class="px-2">
                    <a wire:navigate href="{{ route('cms.page', 'how-it-works') }}"
                        class="text-gray-300 hover:text-white">How It Works</a>
                </div>
                @foreach ($navLinks as $navLink)
                    <div class="group px-2">
                        <a href="#" class="text-gray-300 hover:text-white"
                            onclick="toggleSubMenu('{{ $navLink->id }}-menu')">
                            {{ $navLink->name }}
                        </a>
                        <x-dropdown-menu :categoryId="$navLink->id" :subCategories="$navLink->children" :label="$navLink->name" :products="$navLink->navProducts"
                            submenuId="{{ $navLink->id }}-menu" />
                    </div>
                @endforeach
            </nav>

        </div>

        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-0 bg-transparent z-50 hidden overflow-y-auto">
            <div class="fixed top-0 left-0 w-full bg-site-bg h-full p-4">
                <div class="flex justify-between items-center">
                    <a href="{{ route('home') }}" class="w-auto">
                        <img src="{{ asset(app_setting('site_logo', 'frontend/images/logo.svg')) }}" alt="Logo">
                    </a>
                    <button id="close-sidebar" onclick="closeSidebar()" class="focus:outline-none">
                        <img src="{{ asset('frontend/images/icons/close.svg') }}" alt="Close">
                    </button>
                </div>
                <nav class="flex flex-col space-y-4 h-full mt-5 pb-40 overflow-y-auto">
                    <div class="px-1">
                        <a href="{{ route('home') }}" class="!text-black-3 uppercase hover:!text-gray-800">Home</a>
                    </div>
                    <div class="px-1">
                        <a wire:navigate href="{{ route('cms.page', 'how-it-works') }}"
                            class="!text-black-3 uppercase hover:!text-gray-800">How It Works</a>
                    </div>
                    @foreach ($navLinks as $navLink)
                    <div class="group px-1 relative">
                        <a href="#" data-dropdown-toggle="flower-menu-mobile"
                            class="!text-black-3 uppercase hover:!text-gray-800 flex items-center justify-between"
                            onclick="toggleSubMenu('{{$navLink->id}}-menu-mobile')">
                            <span>{{$navLink->name}}</span>
                            <img src="{{ asset('frontend/images/icons/chevron-down.svg') }}" alt="Back to Shopping">
                        </a>
                        <x-mobile-dropdown-menu :categoryId="$navLink->id" :subCategories="$navLink->children" :label="$navLink->name" :products="$navLink->navProducts"
                            submenuId="{{ $navLink->id }}-menu-mobile" />
                    </div>
                    @endforeach
                </nav>
            </div>
        </div>
    </header>

</div>
