<style>
  .product-dropdown {
    position: relative;
    width: 100%;
  }

  .custom-product-select {
    position: relative;
    /* Add this to ensure the dropdown is contained */
  }

  .aj-dropdown-menu {
    display: none;
    border: 1px solid #ccc;
    overflow-y: auto;
    z-index: 1000;
    position: absolute;
    width: 96%;
    max-height: 200px;
  }

  .dropdown-item {
    display: flex;
    align-items: center;
    padding: 10px;
    cursor: pointer;
  }

  .dropdown-item:hover {
    background-color: #f0f0f0;
  }

  .product-img {
    width: 30px;
    height: 30px;
    margin-right: 10px;
  }

  .selected-products-list {
    margin-top: 15px;
  }

  .selected-product {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }

  .selected-product img {
    width: 30px;
    height: 30px;
    margin-right: 10px;
  }

  .remove-product {
    cursor: pointer;
    color: red;
    margin-left: 10px;
  }
</style>

<div class="row mt-4 p-4 main-block" style="
    background: #d9dcdd;">

  <div class="col-8">
    {{-- Form to Add New Toppings --}}
    <div class="add-topping-form">
      <h6 class="fw-normal mb-3">Search Products</h6>
      <div id="topping-container">
        <div class="row align-items-end mb-2">

          <div class="col-md-12 custom-product-select">
            <input type="text" id="product-search" placeholder="Search for products" class="form-control">
            <div class="aj-dropdown-menu form-control" id="dropdownMenu">
              <!-- Products will be displayed here -->
            </div>
          </div>



        </div>
      </div>
    </div>

  </div>


  <div class="col-4 bg-white" id="topping-section">
    <div class="toppings-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <h6 class="fw-normal p-2">Related Products
      </h6>

      <input type="hidden" id="related_products_input" name="related_products">
      <div id="selected-products" class="selected-products-list">
        @if (isset($existing_related_products) && count($existing_related_products) > 0)
        @foreach ($existing_related_products as $item)
        <div class="selected-product" data-id="{{ $item->relatedproduct->id }}">
          <img src="{{ asset('/images/' . $item->relatedproduct->image_name) }}"
            alt="{{ $item->relatedproduct->name }}">
          <span>{{ $item->relatedproduct->name }}</span>
          <span class="remove-product" data-id="{{ $item->relatedproduct->id }}">✕</span>
        </div>
        @endforeach
        @endif
      </div>
    </div>
  </div>

</div>











{{-- updated script --}}
<script>
  document.getElementById('dropdownMenu').style.display='none';
  document.addEventListener('DOMContentLoaded', function () {
      const productSearch = document.getElementById('product-search');
      const dropdownMenu = document.getElementById('dropdownMenu');
      const selectedProductsContainer = document.getElementById('selected-products');
      const relatedProductsInput = document.getElementById('related_products_input');

      // Load the existing related product IDs from the database (converted to JSON)
      let selectedProducts = @json(isset($existing_related_products) ? $existing_related_products->pluck('relatedproduct.id') : []);
      selectedProducts = selectedProducts.map(id => String(id));  // Convert them to strings

      let allProducts = @json($allproducts);  // Assuming products are passed to the frontend as JSON
      let searchValue = '';
      let loadedProducts = 5;  // Initially showing 5 products
      const productsToLoad = 3;  // Number of products to load per scroll

      // Initial display of products
      displayProducts(allProducts.slice(0, loadedProducts));

      // Preload the existing selected products and update the list
      updateSelectedProductsList();

      // Show dropdown on focus
      productSearch.addEventListener('click', function () {
          dropdownMenu.style.display = 'block';
      });

      // Hide dropdown when clicking outside
      document.addEventListener('click', function (e) {
          if (!e.target.closest('.custom-product-select')) {
              dropdownMenu.style.display = 'none';
          }
      });

      // Search for products when typing
      productSearch.addEventListener('input', function () {
          searchValue = this.value.toLowerCase();
          loadedProducts = productsToLoad; // Reset to initial load count after search
          const filteredProducts = allProducts.filter(product => 
              product.name.toLowerCase().includes(searchValue)
          );
          displayProducts(filteredProducts.slice(0, loadedProducts));  // Show initial products based on search
      });

      // Handle product selection/toggling
      dropdownMenu.addEventListener('click', function (e) {
          const productElement = e.target.closest('.product-option');
          if (productElement) {
              const productId = productElement.getAttribute('data-id');
              const productName = productElement.getAttribute('data-name');
              const productImage = productElement.getAttribute('data-image');

              // Check if the product is already selected (ensure comparison as strings)
              if (selectedProducts.includes(String(productId))) {
                  // If product is already selected, remove it
                  selectedProducts = selectedProducts.filter(id => id != productId);
              } else {
                  // If product is not selected, add it
                  selectedProducts.push(String(productId));
              }
              updateSelectedProductsList();
          }
      });

      // Infinite scroll for dropdown
      dropdownMenu.addEventListener('scroll', function () {
          if (dropdownMenu.scrollTop + dropdownMenu.clientHeight + 10 >= dropdownMenu.scrollHeight) {
              loadMoreProducts();
          }
      });

      // Load more products on scroll
      function loadMoreProducts() {
          const filteredProducts = allProducts.filter(product =>
              product.name.toLowerCase().includes(searchValue)
          );

          if (loadedProducts >= filteredProducts.length) {
              // No more products to load
              console.log("No more products to load");
              return;
          }

          const moreProducts = filteredProducts.slice(loadedProducts, loadedProducts + productsToLoad);
          loadedProducts += productsToLoad;

          displayProducts(moreProducts, false);  // Append new products to dropdown
      }

      // Display products in the dropdown
      function displayProducts(products, reset = true) {
          if (reset) {
              dropdownMenu.innerHTML = '';  
          }

          if (products.length === 0) {
              dropdownMenu.innerHTML = '<div>No products found</div>';
              return;
          }

          products.forEach(product => {
              const productElement = document.createElement('div');
              productElement.classList.add('dropdown-item', 'product-option');
              productElement.setAttribute('data-id', product.id);
              productElement.setAttribute('data-name', product.name);
              productElement.setAttribute('data-image', "{{ asset('/images/' ) }}" + product.image_name);
              
              const isSelected = selectedProducts.includes(String(product.id)) ? 'selected' : '';

              productElement.innerHTML = `
                  <img src="{{ asset('/images/') }}/${product.image_name}" alt="${product.name}" class="product-img">
                  <span>${product.name}</span>
              `;

              dropdownMenu.appendChild(productElement);
          });

          dropdownMenu.style.display = 'block';  
      }

      // Update the selected products list and hidden input
      function updateSelectedProductsList() {
          selectedProductsContainer.innerHTML = '';

          selectedProducts.forEach(productId => {
              const product = allProducts.find(p => p.id == productId);
              const productElement = document.createElement('div');
              productElement.classList.add('selected-product');
              productElement.innerHTML = `
                  <img src="{{ asset('/images/' ) }}/${product.image_name}" alt="${product.name}">
                  <span>${product.name}</span>
                  <span class="remove-product" data-id="${product.id}">✕</span>
              `;
              selectedProductsContainer.appendChild(productElement);
          });

          // Update hidden input
          relatedProductsInput.value = selectedProducts.join(',');

          // Add event listeners to remove products
          document.querySelectorAll('.remove-product').forEach(removeBtn => {
              removeBtn.addEventListener('click', function () {
                  const productIdToRemove = this.getAttribute('data-id');
                  selectedProducts = selectedProducts.filter(id => id != productIdToRemove);
                  updateSelectedProductsList();
              });
          });
      }
  });
</script>





{{-- <script>
  document.addEventListener('DOMContentLoaded', function () {
      const productSearch = document.getElementById('product-search');
      const dropdownMenu = document.getElementById('dropdownMenu');
      const selectedProductsContainer = document.getElementById('selected-products');
      const relatedProductsInput = document.getElementById('related_products_input');

      let selectedProducts = [];
      let allProducts = @json($allproducts);  // Assuming products are passed to the frontend as JSON
      let searchValue = '';
      let loadedProducts = 5;  // Initially showing 3 products
      const productsToLoad = 3;  // Number of products to load per scroll

      // Initial display of products
      displayProducts(allProducts.slice(0, loadedProducts));

      // Show dropdown on focus
      productSearch.addEventListener('focus', function () {
          dropdownMenu.style.display = 'block';
      });

      // Hide dropdown when clicking outside
      document.addEventListener('click', function (e) {
          if (!e.target.closest('.custom-product-select')) {
              dropdownMenu.style.display = 'none';
          }
      });

      // Search for products when typing
      productSearch.addEventListener('input', function () {
          searchValue = this.value.toLowerCase();
          loadedProducts = productsToLoad; // Reset to initial load count after search
          const filteredProducts = allProducts.filter(product => 
              product.name.toLowerCase().includes(searchValue)
          );
          displayProducts(filteredProducts.slice(0, loadedProducts));  // Show initial products based on search
      });

      // Handle product selection
      dropdownMenu.addEventListener('click', function (e) {
          if (e.target.closest('.product-option')) {
              const productId = e.target.closest('.product-option').getAttribute('data-id');
              const productName = e.target.closest('.product-option').getAttribute('data-name');
              const productImage = e.target.closest('.product-option').getAttribute('data-image');

              if (!selectedProducts.includes(productId)) {
                  selectedProducts.push(productId);
                  updateSelectedProductsList();
              }
          }
      });

      // Infinite scroll for dropdown
      dropdownMenu.addEventListener('scroll', function () {
          if (dropdownMenu.scrollTop + dropdownMenu.clientHeight +10 >= dropdownMenu.scrollHeight) {
              loadMoreProducts();
          }
      });

      // Load more products on scroll
      function loadMoreProducts() {
          const filteredProducts = allProducts.filter(product =>
              product.name.toLowerCase().includes(searchValue)
          );

          if (loadedProducts >= filteredProducts.length) {
              // No more products to load
              console.log("No more products to load");
              return;
          }

          const moreProducts = filteredProducts.slice(loadedProducts, loadedProducts + productsToLoad);
          loadedProducts += productsToLoad;

          displayProducts(moreProducts, false);  // Append new products to dropdown
      }


      // Display products in the dropdown
      function displayProducts(products, reset = true) {
          if (reset) {
              dropdownMenu.innerHTML = '';  
          }

          if (products.length === 0) {
              dropdownMenu.innerHTML = '<div>No products found</div>';
              return;
          }

          products.forEach(product => {
              const productElement = document.createElement('div');
              productElement.classList.add('dropdown-item', 'product-option');
              productElement.setAttribute('data-id', product.id);
              productElement.setAttribute('data-name', product.name);
              productElement.setAttribute('data-image', "{{ asset('/images/' ) }}" + product.image_name);
              
              productElement.innerHTML = `
                  <img src="{{ asset('/images/') }}/${product.image_name}" alt="${product.name}" class="product-img">
                  <span>${product.name}</span>
              `;
              dropdownMenu.appendChild(productElement);
          });

          dropdownMenu.style.display = 'block';  
      }

      // Update the selected products list and hidden input
      function updateSelectedProductsList() {
          selectedProductsContainer.innerHTML = '';

          selectedProducts.forEach(productId => {
              const product = allProducts.find(p => p.id == productId);
              const productElement = document.createElement('div');
              productElement.classList.add('selected-product');
              productElement.innerHTML = `
                  <img src="{{ asset('/images/' ) }}/${product.image_name}" alt="${product.name}">
                  <span>${product.name}</span>
                  <span class="remove-product" data-id="${product.id}">✕</span>
              `;
              selectedProductsContainer.appendChild(productElement);
          });

          // Update hidden input
          relatedProductsInput.value = selectedProducts.join(',');

          // Add event listeners to remove products
          document.querySelectorAll('.remove-product').forEach(removeBtn => {
              removeBtn.addEventListener('click', function () {
                  const productIdToRemove = this.getAttribute('data-id');
                  selectedProducts = selectedProducts.filter(id => id != productIdToRemove);
                  updateSelectedProductsList();
              });
          });
      }
  });
</script> --}}