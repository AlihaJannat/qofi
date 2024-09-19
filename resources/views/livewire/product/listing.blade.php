<div class="">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    <div class="mt-4 pagination">
        {{ $products->links('livewire.product.pagination') }}
    </div>
</div>
