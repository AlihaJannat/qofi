<main class="login-container flex justify-center items-center py-12">
    <div class="container mx-auto flex justify-center">
        <div class="w-full max-w-2xl p-10 rounded shadow-sm bg-card-bg">
            <h2 class="text-4xl font-extrabold text-black-5 mb-8">Log In</h2>
            @if (session()->has('message'))
                <div class="mb-4 px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mb-4 px-4 py-3 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            <form wire:submit="attemptLogin">
                <div class="mb-4">
                    <x-input-field label="Email" wire:model="email" id="email" type="email" name="email"
                        placeholder="demo@email.com" />
                </div>
                <div class="mb-4">
                    <x-input-field label="Password" wire:model="password" id="password" type="password" name="password" autocomplete="off"
                        placeholder="Password" />
                </div>
                <div class="flex justify-between">
                    <div class="mb-4 flex">
                        <input type="checkbox" wire:model="keepMeLogin" id="rememberMe" class="form-checkbox h-4 w-4 text-pink-500">
                        <label for="rememberMe" class="ml-2 block font-medium text-sm text-black-2">Remember
                            me</label>
                    </div>
                    <div class="mb-4">

                        <a href="{{ route('forget.password') }}" wire:navigate
                            class="text-sm text-[#F38181] font-bold hover:text-btn-h-pink block mt-1">Forgot
                            Password?</a>
                    </div>
                </div>

                <button type="submit"
                    class="w-full mb-4 flex justify-center shadow-sm bg-btn-pink hover:bg-btn-h-pink text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring focus:ring-pink-500 focus:ring-opacity-50 text-sm">
                    LOGIN
                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.5312 9.46875C11.8125 9.78125 11.8125 10.25 11.5312 10.5312L6.53125 15.5312C6.21875 15.8438 5.75 15.8438 5.46875 15.5312C5.15625 15.25 5.15625 14.7812 5.46875 14.5L9.9375 10.0312L5.46875 5.53125C5.15625 5.25 5.15625 4.78125 5.46875 4.5C5.75 4.1875 6.21875 4.1875 6.5 4.5L11.5312 9.46875Z"
                            fill="white" />
                    </svg>
                </button>
                <button type="button"
                    class="my-4 w-full flex justify-center shadow-sm bg-white hover:bg-slate-100 font-semibold py-2 px-4 rounded focus:outline-none focus:ring focus:ring-gray-400 focus:ring-opacity-50">
                    <img src="{{ asset('frontend/images/logos/google.svg') }}" class="mr-4" alt=""
                        srcset="">
                    <span class="text-gray-500 font-semibold">
                        Continue
                        with Google
                    </span>
                </button>
                <hr class="border-[#F38181]">
                <p class="mt-4 text-sm text-gray-500 text-center">No account yet?
                    <a wire:navigate href="{{ route('register') }}" class="text-black-5 hover:text-black-3">Sign
                        Up</a>
                </p>
            </form>
        </div>
    </div>
</main>