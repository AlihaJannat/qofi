@if ($paginator->hasPages())
    <div class="flex items-center my-2">
        
        @if ( ! $paginator->onFirstPage())
            {{-- First Page Link --}}
            {{-- <a
            class="mx-1 px-3 py-2 bg-btn-pink border-2 border-btn-pink text-white font-bold text-center hover:bg-btn-h-pink hover:border-btn-h-pink rounded-lg cursor-pointer"
            wire:click="gotoPage(1)"
            >
            <img src="{{ asset('frontend/images/icons/left-white.svg') }}" alt="left">
            </a> --}}
            {{-- Previous Page Link --}}
            <a
                class="mx-1 px-3 py-2 bg-btn-pink border-2 border-btn-pink text-white font-bold text-center hover:bg-btn-h-pink hover:border-btn-h-pink rounded-lg cursor-pointer"
                wire:click="previousPage"
            >
            <img src="{{ asset('frontend/images/icons/left-white.svg') }}" alt="left">
            </a>
        @endif

        <!-- Pagination Elements -->
        @foreach ($elements as $element)
            @if (is_array($element))
                @php
                    $ellipsis = false;
                @endphp

                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <!-- Active Page -->
                        <span class="mx-1 px-3 py-2 border-2 border-btn-h-pink bg-btn-h-pink text-white font-bold text-center rounded-lg cursor-pointer">{{ $page }}</span>
                        @php
                            $ellipsis = true;
                        @endphp
                    @elseif ($page == 1 || $page == 2 || $page == $paginator->lastPage() || $page == $paginator->lastPage() - 1 || ($page >= $paginator->currentPage() - 1 && $page <= $paginator->currentPage() + 1))
                        <!-- Always show the first two and last two pages, and the two pages before and after the current page -->
                        <a class="mx-1 px-3 py-2 border-2 border-btn-pink bg-btn-pink text-white font-bold text-center hover:bg-btn-h-pink hover:border-btn-h-pink rounded-lg cursor-pointer" wire:click="gotoPage({{ $page }})">{{ $page }}</a>
                        @php
                            $ellipsis = true;
                        @endphp
                    @elseif ($ellipsis)
                        <!-- Add ellipses for gaps -->
                        <span class="flex items-center mx-1">
                            <div class="text-black-5 w-4 flex justify-center">
                                <span class="font-bold">.</span>
                                <span class="font-bold">.</span>
                                <span class="font-bold">.</span>
                            </div>
                        </span>
                        @php
                            $ellipsis = false;
                        @endphp
                    @endif
                @endforeach
            @endif
        @endforeach
        
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="mx-1 px-3 py-2 bg-btn-pink border-2 border-btn-pink text-white font-bold text-center hover:bg-btn-h-pink hover:border-btn-h-pink rounded-lg cursor-pointer"
                wire:click="nextPage"
                rel="next">
                <img src="{{ asset('frontend/images/icons/right-white.svg') }}" alt="right">
            </a>
        @endif
    </div>
@endif
