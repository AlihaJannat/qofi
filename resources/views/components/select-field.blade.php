<div>
    @if ($showLabel)
        <label for="{{ $id }}" class="block text-sm font-medium text-black-5">{{ $label }}</label>
    @endif
    <div class="relative mt-1">
        <select id="{{ $id }}" name="{{ $name }}"
            class="mt-1 block w-full px-3 py-2 focus:outline-none focus:border-pink-500 focus:ring focus:ring-pink-500 focus:ring-opacity-50 {{ $className }}"
            {{ $attributes }}>
            {{ $slot }}
        </select>
    </div>
</div>
