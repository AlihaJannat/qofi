<style>
  .topping-details {
    margin-left: 5px;
    display: flex;
    justify-content: space-between
  }

  .topping-details strong {
    margin-right: 5px;

  }

  #status-loading {
    display: none
  }

  #success-status-update {
    color: green;
    float: right;
    font-style: italic;
    display: none
  }

  .topping-card-block {
    display: flex;
    align-items: center;
    margin-right: 3px;
    justify-content: space-between
  }
</style>

@php
$productId = (isset($product) ? $product->id : 0);
@endphp
<div class="row mt-4 p-4 main-block" style="
    background: #d9dcdd;">

  <div class="col-8">
    {{-- Form to Add New Toppings --}}
    <div class="add-topping-form">
      <h6 class="fw-normal mb-3">Add New Topping</h6>
      <div id="topping-container">
        <div class="row align-items-end mb-2">

          <div class="col-md-5">
            <div class="form-group">
              <label>Topping Name</label>
              <input type="text" class="form-control" name="topping_name[]" placeholder="Enter Topping Name">
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label>Topping Price</label>
              <input type="text" class="form-control" name="topping_price[]" placeholder="Enter Topping Price">
            </div>
          </div>

          @if (isset($existing_toppings) && count($existing_toppings) > 0 || $productId)
          <div class="col-md-2">
            <div class="form-group">
              <button type="button" class="btn btn-success" id="add-topping-btn" title="Add Topping"
                style="margin-top: 30px; ">Add</button>

            </div>
          </div>
          @else
          <div class="col-md-2">
            <div class="form-group">
              <button type="button" class="btn btn-success add-new-topping-fields">+</button>
            </div>
          </div>
          @endif

        </div>
      </div>
    </div>

  </div>

  <!-- Toppings will be dynamically injected here if none exist initially -->
  @if (isset($existing_toppings) && count($existing_toppings) > 0)
  <div class="col-4 bg-white" id="topping-section">
    <div class="toppings-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <h6 class="fw-normal p-2">Toppings
        <span id="success-status-update">updated</span>
        <span style="float:right;display:none" id="status-loading">
          <img src="{{asset('admindic/img/icons/loader-black.svg')}}">
        </span>
      </h6>

      @foreach ($existing_toppings as $topping)
      <div
        class="topping-card topping-card-block border rounded-lg p-2 shadow-md flex items-center justify-between mb-1">
        <div class="topping-card-block">
          <input type="checkbox" name="topping_active[]" value="{{ $topping->status }}" {{ $topping->status ?
          'checked'
          : '' }} class="form-checkbox h-5 w-5 text-blue-600 toggle-topping-status" data-topping-id="{{ $topping->id
          }}">
          <div class="topping-details">
            <strong class="block text-lg font-semibold">{{ $topping->name }}</strong>
            <span class="text-gray-500">{{app_setting('site_currency')}} {{ $topping->price }}</span>
          </div>
        </div>
        <i class="fas fa-trash delete-topping" data-topping-id="{{ $topping->id }}"></i>
      </div>
      @endforeach
    </div>
  </div>
  @endif


</div>


@if ($productId != 0)
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Adding topping for existing products
    document.getElementById('add-topping-btn').addEventListener('click', function() {
      const toppingName = document.querySelector('input[name="topping_name[]"]').value;
      const toppingPrice = document.querySelector('input[name="topping_price[]"]').value;

      if (toppingName && toppingPrice) {
          $('#status-loading').show();

          fetch('{{ route('vendor.product.add.topping') }}', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
              },
              body: JSON.stringify({
                  name: toppingName,
                  price: toppingPrice,
                  product_id: {{ $productId }}, // Pass the correct product ID here
              })
          })
          .then(response => response.json())
          .then(data => {
              $('#status-loading').hide();

              if (data.success) {
                  // showSuccessMessage();

                  // Check if the topping section exists, otherwise create it
                  let toppingSection = document.getElementById('topping-section');
                  if (!toppingSection) {
                      // Create a new topping section if it doesn't exist
                      toppingSection = document.createElement('div');
                      toppingSection.classList.add('col-4', 'bg-white');
                      toppingSection.id = 'topping-section';

                      toppingSection.innerHTML = `
                          <div class="toppings-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                              <h6 class="fw-normal p-2">Toppings
                                  <span id="success-status-update">updated</span>
                                  <span style="float:right;display:none" id="status-loading">
                                      <img src="{{asset('admindic/img/icons/loader-black.svg')}}">
                                  </span>
                              </h6>
                          </div>
                      `;

                      // Append the new topping section to the container
                      document.querySelector('.main-block').appendChild(toppingSection);
                  }

                  // Add the new topping to the toppings list
                  let status = data.topping.status ? 'checked' : '';
                  
                  const newToppingBlock = `
                      <div class="topping-card topping-card-block border rounded-lg p-2 shadow-md flex items-center justify-between mb-1">
                          <div class="topping-card-block">
                            <input type="checkbox" name="topping_active[]" value="${data.topping.status}" ${status} class="form-checkbox h-5 w-5 text-blue-600 toggle-topping-status" data-topping-id="${data.topping.id}">
                            <div class="topping-details">
                              <strong class="block text-lg font-semibold">${data.topping.name}</strong>
                              <span class="text-gray-500">{{app_setting('site_currency')}} ${data.topping.price}</span>
                            </div>
                          </div>
                          <i class="fas fa-trash delete-topping" data-topping-id="${data.topping.id}"></i>
                        </div>
                  `;

                  // Insert the new topping in the list
                  toppingSection.querySelector('.toppings-list').insertAdjacentHTML('beforeend', newToppingBlock);

                  // Clear the input fields
                  document.querySelector('input[name="topping_name[]"]').value = '';
                  document.querySelector('input[name="topping_price[]"]').value = '';
              } else {
                  alert('Error saving topping');
              }
          })
          .catch(error => console.error('Error:', error));
      }
  });



    // document.getElementById('add-topping-btn').addEventListener('click', function() {
    //   const toppingName = document.querySelector('input[name="topping_name"]').value;
    //   const toppingPrice = document.querySelector('input[name="topping_price"]').value;

    //   if (toppingName && toppingPrice) {
    //     $('#status-loading').show();
        
    //     fetch('{{ route('vendor.product.add.topping') }}', {
    //       method: 'POST',
    //       headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}',
    //       },
    //       body: JSON.stringify({
    //         name: toppingName,
    //         price: toppingPrice,
    //         product_id: {{ $productId }}, // Pass the correct product ID here
    //       })
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //       $('#status-loading').hide();
    //       if (data.success) {
    //         showSuccessMessage();
            
    //         // Add new topping to the list in the frontend
    //         let status = data.topping.status ? 'checked' : '';
    //         const newToppingBlock = `
    //           <div class="topping-card border rounded-lg p-2 shadow-md flex items-center justify-between mb-1">
    //             <div class="topping-card-block">
    //               <input type="checkbox" name="topping_active[]" value="${data.topping.status}" ${status} class="form-checkbox h-5 w-5 text-blue-600 toggle-topping-status" data-topping-id="${data.topping.id}">
    //               <div class="topping-details">
    //                 <strong class="block text-lg font-semibold">${data.topping.name}</strong>
    //                 <span class="text-gray-500">{{app_setting('site_currency')}} ${data.topping.price}</span>
    //               </div>
    //             </div>
    //             <i class="fas fa-trash delete-topping" data-topping-id="${data.topping.id}"></i>
    //           </div>`;
    //         document.querySelector('.toppings-list').insertAdjacentHTML('beforeend', newToppingBlock);

    //         // Clear input fields
    //         document.querySelector('input[name="topping_name"]').value = '';
    //         document.querySelector('input[name="topping_price"]').value = '';
    //       } else {
    //         alert('Error saving topping');
    //       }
    //     })
    //     .catch(error => console.error('Error:', error));
    //   }
    // });

    // Updating status
    // Attach the event listener to a parent container that will contain the toppings
    document.querySelector('.toppings-list').addEventListener('change', function(event) {
      // Check if the event target is the checkbox with the class 'toggle-topping-status'
      if (event.target.classList.contains('toggle-topping-status')) {
        const checkbox = event.target;
        const toppingId = checkbox.getAttribute('data-topping-id');
        const isActive = checkbox.checked ? 1 : 0;
        console.log('updating status of id ' + toppingId);

        $('#status-loading').show();
        fetch('{{ route('vendor.product.update.topping.status') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
          body: JSON.stringify({
            topping_id: toppingId,
            status: isActive
          })
        })
        .then(response => response.json())
        .then(data => {
          $('#status-loading').hide();
          if (data.success) {
            showSuccessMessage();
          } else {
            alert('Error updating topping status');
          }
        })
        .catch(error => console.error('Error:', error));
      }
    });


    // Deleting topping
    document.querySelector('.toppings-list').addEventListener('click', function(event) {
      if (event.target.classList.contains('delete-topping')) {
        const deleteIcon = event.target;
        const toppingId = deleteIcon.getAttribute('data-topping-id');

        $('#status-loading').show();
        fetch('{{ route('vendor.product.delete.topping') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
          body: JSON.stringify({
            topping_id: toppingId
          })
        })
        .then(response => response.json())
        .then(data => {
          $('#status-loading').hide();
          if (data.success) {
            showSuccessMessage();
            deleteIcon.closest('.topping-card').remove(); // Remove the card
          } else {
            alert('Error deleting topping');
          }
        })
        .catch(error => console.error('Error:', error));
      }
    });


    function showSuccessMessage(){
      var successMessage = document.getElementById('success-status-update');
      successMessage.style.display = 'block';
      setTimeout(function() {
        successMessage.style.display = 'none'; 
      }, 2000);
    }
  });
</script>
@else
<script>
  document.addEventListener('DOMContentLoaded', function() {
  // For creating a new product and adding multiple toppings
    $('.add-new-topping-fields').click(function() {
      var newField = `
        <div class="row align-items-end mb-2">
          <div class="col-md-5">
            <div class="form-group">
              <label>Topping Name</label>
              <input type="text" class="form-control" name="topping_name[]" placeholder="Enter Topping Name">
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label>Topping Price</label>
              <input type="text" class="form-control" name="topping_price[]" placeholder="Enter Topping Price">
            </div>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-topping">-</button>
          </div>
        </div>`;
      $('#topping-container').append(newField); // Append new fields
    });

    // Remove topping fields
    $(document).on('click', '.remove-topping', function() {
      $(this).closest('.row').remove(); // Remove the closest row
    });
  });
  
</script>
@endif