<main class="login-container flex justify-center items-center py-12">
    <div class="container mx-auto flex justify-center">
        <div class="w-full max-w-2xl p-4 sm:p-10 rounded shadow-sm bg-card-bg">
            <h2 class="text-4xl font-extrabold text-black-5 mb-8">Sign Up</h2>
            <form>
                <div class="flex flex-col sm:flex-row mb-4 gap-4">
                    <div class="flex-1">
                        <x-input-field label="First Name" id="first_name" type="text" name="first_name"
                            placeholder="John" className="custom-class" />
                    </div>
                    <div class="flex-1">
                        <x-input-field label="Last Name" id="last_name" type="text" name="last_name" placeholder="Doe" />
                    </div>
                </div>
                <div class="mb-4">
                    <x-input-field label="Username" id="username" type="text" name="username" placeholder="Username" />
                </div>
                <div class="mb-4">
                    <x-input-field label="Email" id="email" type="email" name="email" placeholder="Email" />
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-black-5">Phone</label>
                    <div class="flex">
                        <input type="text" readonly value="+965"
                            class="mt-1 block w-20 px-3 py-2 rounded-md focus:outline-none ">
                        <input type="number" id="phone"
                            class="mt-1 block w-full px-3 py-2 rounded-md focus:outline-none focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50"
                            placeholder="55554444">
                    </div>
                </div>
                <div class="mb-4 relative">
                    <x-input-field label="Password" wire:model="password" id="password" type="password" name="password" autocomplete="off"
                        placeholder="Password" />
                </div>

                <button type="submit"
                    class="w-full mb-4 flex justify-center shadow-sm bg-btn-pink hover:bg-btn-h-pink text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring focus:ring-pink-500 focus:ring-opacity-50 text-sm">
                    REGISTER
                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.5312 9.46875C11.8125 9.78125 11.8125 10.25 11.5312 10.5312L6.53125 15.5312C6.21875 15.8438 5.75 15.8438 5.46875 15.5312C5.15625 15.25 5.15625 14.7812 5.46875 14.5L9.9375 10.0312L5.46875 5.53125C5.15625 5.25 5.15625 4.78125 5.46875 4.5C5.75 4.1875 6.21875 4.1875 6.5 4.5L11.5312 9.46875Z"
                            fill="white" />
                    </svg>
                </button>
                <div class="flex justify-between">
                    <div class="mb-4 flex">
                        <input type="checkbox" id="terms" class="form-checkbox h-4 w-4" required>
                        <label for="terms" class="ml-2 block font-medium text-sm text-black-2">Terms & Condition:
                            Users must agree to the terms and privacy policy</label>
                    </div>
                </div>
                <hr class="border-[#F38181]">
                <p class="mt-4 text-sm text-gray-500 text-center">Already have an account? 
                    <a wire:navigate 
                        href="{{ route('login') }}" class="text-black-5 hover:text-black-3">Log In</a>
                    </p>
            </form>
        </div>
    </div>
</main>