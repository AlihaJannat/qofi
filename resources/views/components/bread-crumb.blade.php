<div class="w-full h-full flex justify-center items-center my-4">
    <div class="flex self-stretch items-center gap-2">
        @foreach ($previousLinks as $link)
            <a href="{{$link['link']}}"
                class="text-center text-black hover:no-underline hover:text-black text-sm font-normal leading-5">
                {{$link['title']}}
            </a>
            <i class="fas fa-angle-right"></i>
        @endforeach
        <div class="text-center text-gray-700 text-sm font-semibold leading-5">
            {{$currentPage}}
        </div>
    </div>
</div>
