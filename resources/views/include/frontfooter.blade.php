<footer class="bg-black-3 text-gray-300">
    <div class="container mx-auto py-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="footer-brand gap-4 flex flex-col md:col-span-2">
                <a href="{{route('home')}}">
                    <img src="{{ asset(app_setting('site_footer_logo', 'frontend/images/logo.svg')) }}" alt=""
                        srcset="">
                </a>
                <div class="flex gap-2">
                    <img src="{{ asset('frontend/images/logos/fb.svg') }}" alt="">
                    <img src="{{ asset('frontend/images/logos/insta.svg') }}" alt="">
                    <img src="{{ asset('frontend/images/logos/x.svg') }}" alt="">
                    <img src="{{ asset('frontend/images/logos/linkedIn.svg') }}" alt="">
                    <img src="{{ asset('frontend/images/logos/yt.svg') }}" alt="">
                </div>
                <div>
                    <p class="text-sm">We Accept:</p>
                    <div class="flex mt-2">
                        <img src="{{ asset('frontend/images/logos/paymethod.svg') }}" alt="Visa" class="h-6 mr-4">
                    </div>
                </div>
            </div>
            <div class="footer-links flex flex-col md:grid md:grid-cols-2 gap-4 md:gap-8 md:col-span-1">
                <div>
                    <h3 class="text-lg font-semibold">Quick Links</h3>
                    <a href="#" class="block mt-2 text-black-d hover:text-white">Home</a>
                    <a href="{{route('vendor.login')}}" class="block mt-2 text-black-d hover:text-white">Vendor Login</a>
                    <a wire:navigate href="{{route('login')}}" class="block mt-2 text-black-d hover:text-white">Customer Login</a>
                    <a href="#" class="block mt-2 text-black-d hover:text-white">Join Abi</a>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Help</h3>
                    <a wire:navigate href="{{route('cms.page', 'about-us')}}" class="block mt-2 text-black-d hover:text-white">About Us</a>
                    <a href="#" class="block mt-2 text-black-d hover:text-white">Careers</a>
                    <a href="#" class="block mt-2 text-black-d hover:text-white">Contact Us</a>
                    <a wire:navigate href="{{route('cms.page', 'how-it-works')}}" class="block mt-2 text-black-d hover:text-white">How It Works</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-8 flex flex-col md:flex-row items-center justify-between border-t border-slate-50 pt-4">
            <p class="text-black-d">&copy; {{ date('Y') }} {{ app_setting('site_name') }}, All Rights Reserved</p>
            <div class="flex mt-4 md:mt-0">
                <a wire:navigate href="{{route('cms.page', 'term-n-condition')}}" class="text-black-d hover:text-white mr-4">Terms & conditions</a>
                <a wire:navigate href="{{route('cms.page', 'privacy-policy')}}" class="text-black-d hover:text-white mr-4">Privacy Policy</a>
                <a wire:navigate href="{{route('cms.page', 'faqs')}}" class="text-black-d hover:text-white">FAQs</a>
            </div>
        </div>
    </div>
</footer>
