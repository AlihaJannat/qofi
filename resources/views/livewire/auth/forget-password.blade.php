<main class="login-container flex justify-center items-center py-12">
    <div class="container mx-auto flex justify-center">
        <div class="w-full max-w-2xl p-10 rounded shadow-sm bg-card-bg">
            <h2 class="text-4xl font-extrabold text-black-5">Forget Password</h2>
            <p class=" mb-8">Please enter you email below to receive reset password link</p>
            <form wire:submit="requestForget">
                <div class="mb-4">
                    <x-input-field wire:model="email" label="Email" id="email" type="email"
                        placeholder="demo@email.com" name="email" />
                    @error('email')
                        <span class="text-red-500 block ml-2">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full mb-4 flex justify-center shadow-sm bg-btn-pink hover:bg-btn-h-pink text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring focus:ring-pink-500 focus:ring-opacity-50 text-sm">
                    Reset my Password
                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.5312 9.46875C11.8125 9.78125 11.8125 10.25 11.5312 10.5312L6.53125 15.5312C6.21875 15.8438 5.75 15.8438 5.46875 15.5312C5.15625 15.25 5.15625 14.7812 5.46875 14.5L9.9375 10.0312L5.46875 5.53125C5.15625 5.25 5.15625 4.78125 5.46875 4.5C5.75 4.1875 6.21875 4.1875 6.5 4.5L11.5312 9.46875Z"
                            fill="white" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</main>
