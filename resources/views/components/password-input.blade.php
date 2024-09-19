<div>
    <label for="{{ $id }}" class="block text-sm font-medium text-black-5">{{ $label }}</label>
    <div class="relative mt-1">
        <input type="{{ $type }}" id="{{ $id }}" value="{{ $value }}" name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            class="mt-1 block w-full px-3 py-2 rounded-md focus:outline-none focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 {{ $className }}"
            {{ $attributes }}>

        <i
            class="{{ $type == 'password' ? 'bx bx-hide' : 'bx bx-show' }} absolute inset-y-0 right-3 flex items-center toggle-password cursor-pointer text-btn-pink hover:text-btn-h-pink"></i>
    </div>
</div>
