<div class="w-full h-full flex justify-between items-center px-2 py-5 md:!px-10 ">
    <div class="flex flex-col justify-start items-start gap-2 md:gap-4 w-full md:w-[768px]">
        <div class="text-[#E83D5C] text-[8px] md:text-base font-normal">{{$topTitle}}</div>
        <div class="w-full h-auto flex flex-col justify-start items-center gap-2 md:gap-4">
            <div class="w-full text-[#F29896] text-xl md:text-6xl font-normal leading-tight local-shrimp">{{$mainHeading}}
            </div>
            <div class="w-full opacity-70 text-[#393939] text-[8px] md:text-base font-normal">
                {{$desc}}
            </div>
        </div>
    </div>
    <div class="flex flex-col justify-start items-start gap-2 p-0 lg:p-4 min-w-[102px]">
        <div
            class="h-6 md:h-10 px-2 md:px-4 py-1 md:py-2 bg-gradient-to-r from-[#FBEDBC] to-[rgba(251,237,188,0.50)] rounded flex justify-start items-center gap-2">
            <a href="{{$link}}" class="flex justify-start items-center gap-2 md:gap-3 hover:no-underline">
                <div class="text-[#F38181] text-[8px] md:text-sm font-medium leading-tight md:leading-6">{{$btnText}}
                </div>
                <div
                    class="flex items-center justify-center w-2 h-2 md:w-4 md:h-4 text-center text-[#F38181] text-sm lg:text-lg">
                    <i class="fas fa-angle-right fa-xs md:fa-lg"></i>
                </div>
            </a>
        </div>
    </div>
</div>