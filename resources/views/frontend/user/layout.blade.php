@extends('layout.frontapp')

@section('content')
<div class="m-2 md:!m-8 2xl:!m-12">
    <x-page-header heading="My Account" />
    <div class="w-full flex flex-col md:flex-row gap-3">
        {{-- top nav for small devices --}}
        <div class="w-full md:hidden flex justify-between items-center bg-[#FFD2CF] p-4">
            <span class="text-black-5 text-base font-medium">Menu</span>
            <button onclick="toggleUserSidebar()" class="text-black-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        {{-- left side for larger devices --}}
        <div id="sidebarUser" class="hidden md:flex md:w-1/4 flex-col">
            @include('frontend.user.sidebar')
        </div>

        {{-- right side --}}
        @yield('child-content')
    </div>
</div>
@endsection