<div class="px-2 py-5 md:!px-10 relative">
    <div id="{{$id}}" class="owl-carousel owl-theme">
        @for ($i = 0; $i < 4; $i++)
            <div class="item">
                <div
                    class="w-full p-2 md:p-4 bg-[rgba(248,233,233,0.50)] rounded-lg overflow-hidden flex flex-col justify-start items-start gap-4">
                    <div class="w-full">
                        <div class="w-full h-64 lg:h-80 relative bg-[rgba(255,210,207,0.30)] rounded overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-full skeleton"></div>
                        </div>
                        <div class="w-full h-18 flex flex-col justify-start items-start gap-2 py-2">
                            <div class="w-full h-4 flex flex-col justify-start items-start gap-4">
                                <div class="w-full skeleton" style="height: 20px;"></div>
                            </div>
                            <div class="w-full h-[13px] flex flex-col justify-start items-start gap-4">
                                <div class="w-full skeleton" style="height: 15px;"></div>
                            </div>
                            <div class="w-full flex justify-between items-center mt-3">
                                <div class="skeleton" style="width: 50%; height: 20px;"></div>
                                <div class="skeleton" style="width: 30%; height: 20px;"></div>
                            </div>
                        </div>
                        <div class="w-full h-10 flex flex-col justify-start items-start gap-2">
                            <div
                                class="w-full h-10 px-4 py-2 bg-gradient-to-r from-[#FBEDBC] to-[rgba(251,237,188,0.50)] rounded flex justify-start items-center gap-2">
                                <div class="flex-1 h-4 flex justify-center items-center gap-3">
                                    <div class="skeleton" style="width: 80%; height: 20px;"></div>
                                    <div class="w-4 h-4 skeleton"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
