@extends('layout.adminapp')
@section('content')
@php
$superAdmin = auth('admin')->user()->id == 1;
$canCreate = auth('admin')->user()->roleRel?->permissions->contains('name', 'subscription_create') || $superAdmin;
$canEdit = auth('admin')->user()->roleRel?->permissions->contains('name', 'subscription_edit') || $superAdmin;
$canDelete = auth('admin')->user()->roleRel?->permissions->contains('name', 'subscription_delete') || $superAdmin;
@endphp
<style>
    .select2-container {
        z-index: 9999 !important;
    }

    .modal-body {
        overflow-y: auto;
        max-height: 120vh;
        /* Adjust based on your design */
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-header">Subscription</h5>
            {{-- <select id="statusFilter" class="mx-3">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="block">Block</option>
            </select> --}}
        </div>
        <div class="dt-action-buttons text-end pt-3 pt-md-0">
            <div class="dt-buttons btn-group flex-wrap">
                @if ($canCreate)
                <button data-bs-toggle="modal" class="btn create-new btn-primary mx-3"
                    data-bs-target="#subscriptionModal" type="button"><span><i class="bx bx-plus me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New
                            Subscription</span></span></button>
                @endif
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-row-grouping table table-bordered" id="subscriptionTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Shop</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Add Subscription</h3>
                </div>

                <form class="add-new-subscription pt-0" onsubmit="addSubscription(event, this)"
                    action="{{ route('admin.plan.subscription.add') }}">

                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small>(optional)</small></label>
                        <textarea class="form-control" name="description" id="description" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (In Days)</label>
                        <input type="number" class="form-control" name="duration" id="duration" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (In {{app_setting('site_currency')}})</label>
                        <input type="text" class="form-control" name="price" id="price" required />
                    </div>

                    <div class="mb-3">
                        <label>Shops</label>
                        <select name="shop_id[]" id="shops" onchange="addCount(this)" class="form-select shop-select"
                            multiple="multiple">
                            @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}" data-name="{{$shop->name}}">
                                {{ $shop->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Dynamic counts for each shop -->
                    <div id="shop-counts" class="mb-3">
                        <!-- Shop counts will be dynamically inserted here -->
                    </div>
                    <!-- Error message display -->
                    <div id="error-message" class="text-danger mb-3" style="display: none;">
                        <p id="error-text"></p>
                    </div>

                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- show shops modal --}}
<div class="modal fade" id="shopsModal" tabindex="-1" aria-labelledby="shopsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shopsModalLabel">Shops</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Shops table will be inserted here -->
                <table id="shopsTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Shop Name</th>
                            <th>Order Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic shop rows will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


{{-- edit subscription --}}
<div class="modal fade" id="editSubscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Edit Subscription</h3>
                </div>
                <form class="add-new-subscription pt-0" onsubmit="updateSubscription(event, this)"
                    action="{{ route('admin.plan.subscription.update') }}">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small>(optional)</small></label>
                        <textarea class="form-control" name="description" id="edit-description" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (In Days)</label>
                        <input type="number" class="form-control" name="duration" id="edit-duration" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (In {{app_setting('site_currency')}})</label>
                        <input type="text" class="form-control" name="price" id="edit-price" required />
                    </div>
                    <div class="mb-3">
                        <label>Shops</label>
                        <select name="shop_id[]" id="edit-shop" class="form-select shop-select-edit" multiple
                            onchange="updateShopCounts()">
                            @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}" data-name="{{$shop->name}}">
                                {{ $shop->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Dynamic counts for each shop -->
                    <div id="edit-shop-counts" class="mb-3">
                        <!-- Shop counts will be dynamically inserted here -->
                    </div>
                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#subscriptionModal').on('shown.bs.modal', function () {
            $('.shop-select').select2({
                placeholder: "Select Shops",
                width: '100%'
            });
        });
        $('#editSubscriptionModal').on('shown.bs.modal', function () {
            $('.shop-select-edit').select2({
                dropdownParent: $('#editSubscriptionModal'),
                width: '100%' // Adjust based on your layout
            });
        });

        @if ($canEdit)
            //changing the status
            function statusChange(itSelf, id) {
                var button = $(itSelf);
                if (button.hasClass('active')) {
                    Swal.fire({
                            title: `Are you sure you want to mark this Subscription as Block?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.plan.subscription.status') }}",
                                    method: "GET",
                                    data: {
                                        id: id,
                                        status: 0
                                    },
                                    success: function(data) {
                                        button.addClass('btn-warning');
                                        button.addClass('block');
                                        button.html('Block');
                                        button.removeClass('active');
                                        button.removeClass('btn-success');
                                    }
                                })
                            }
                        })
                } else if (button.hasClass('block')) {
                    Swal.fire({
                            title: `Are you sure you want to mark this Subscription as Active?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.plan.subscription.status') }}",
                                    method: "GET",
                                    data: {
                                        id: id,
                                        status: 1
                                    },
                                    success: function(data) {
                                        button.removeClass('btn-warning');
                                        button.removeClass('block');
                                        button.html('Active');
                                        button.addClass('active');
                                        button.addClass('btn-success');
                                    }
                                })
                            }
                        })
                } else {
                    Swal.fire({
                        title: `something went wrong please try later`,
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                }
            }
        @endif

        var deleteSubscription
        var addSubscription
        var editSubscription
        var showShops
        $(document).ready(function() {
            var table = $('#subscriptionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.plan.subscription.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'duration',
                        name: 'duration',
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data) {
                            html = `{{app_setting('site_currency')}} `+data
                            return html
                        },
                    },
                    {
                        data: 'shop',
                        name: 'shop',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    },
                ]
            })

            showShops = function(shops,duration){
                const tableBody = $('#shopsTable tbody');
                    tableBody.empty(); // Clear existing rows

                    // Populate table with shop data
                    shops.forEach(shop => {
                        tableBody.append(`
                            <tr>
                                <td>${shop.name}</td>
                                <td>${shop.order_count} / ${duration}</td> 
                            </tr>
                        `);
                    });
                    // Show the modal
                    $('#shopsModal').modal('show');
            }

            $('#statusFilter').on('change', function() {
                table.ajax.reload(); // Reload the DataTable when the select field changes
            });

            addSubscription = function(e, form) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    success: function(data) {
                        table.draw()
                        $('.btn-reset').click()
                    },
                    error: function(data) {
                        if (data.responseJSON?.message) {
                            Swal.fire({
                                title: `Oops!`,
                                text: data.responseJSON.message,
                                icon: "warning",
                            })
                            return false;
                        }
                        Swal.fire({
                            title: `Something went wrong!`,
                            icon: "warning",
                        })
                    }
                })
            }

            editSubscription = function(sub, id) {
                name = sub.name
                description = sub.description
                duration = sub.duration
                price = sub.price
                shops = sub.shops.map(shop => shop.id); 
                shopCounts = sub.shops.reduce((acc, shop) => {
                    acc[shop.id] = shop.pivot.order_count; 
                    return acc;
                }, {});
                

                $('#edit-id').val(id)
                $('#edit-name').val(name)
                $('#edit-description').val(description)
                $('#edit-duration').val(duration)
                $('#edit-price').val(price)
                $('#edit-shop').val(shops).trigger('change');

                updateShopCounts(shopCounts);

                $('#editSubscriptionModal').modal('show')
            }

            updateSubscription = function(e, form) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    success: function(data) {
                        table.draw()
                        $('.btn-reset').click()
                    },
                    error: function(data) {
                        if (data.responseJSON?.message) {
                            Swal.fire({
                                title: `Oops!`,
                                text: data.responseJSON.message,
                                icon: "warning",
                            })
                            return false;
                        }
                        Swal.fire({
                            title: `Something went wrong!`,
                            icon: "warning",
                        })
                    }
                })
            }

            deleteSubscription = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Subscription, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.plan.subscription.delete') }}",
                                method: "DELETE",
                                data: {
                                    id: id,
                                    _token: "{{csrf_token()}}"
                                },
                                success: function(data) {
                                    table.draw(false);
                                }
                            })
                        }
                    })
            }

            $('.shop-select').select2({
                placeholder: "Select Shops",
                dropdownParent: $('#subscriptionModal')
            });
            $('.shop-select-edit').select2({
                placeholder: "Select Shops",
                dropdownParent: $('#editSubscriptionModal')
            });
            
        });
</script>


<script>
    function addCount(selectElement) {
        const selectedOptions = Array.from(selectElement.selectedOptions);
        const shopCountsDiv = document.getElementById('shop-counts');
        const duration = parseInt(document.getElementById('duration').value) || 0; // Get the subscription duration
        const errorMessageDiv = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');
        const orderPerDay = {{config('constant.ORDERPERDAY')}};

        shopCountsDiv.innerHTML = ''; // Clear existing counts

        selectedOptions.forEach(option => {
            const shopId = option.value;
            const shopName = option.getAttribute('data-name'); // Ensure option is an HTMLOptionElement
            
            const countField = document.createElement('div');
            countField.classList.add('mb-3');
            
            countField.innerHTML = `
                <label for="count-${shopId}">Count for Shop ${shopName}</label>
                <input type="number" name="shop_counts[${shopId}]" id="count-${shopId}" class="form-control" min="0">
            `;
            
            shopCountsDiv.appendChild(countField);
        });

        // Add validation for total counts if needed
        document.querySelectorAll('input[name^="shop_counts"]').forEach(input => {
            input.addEventListener('input', () => {
                const totalCount = Array.from(document.querySelectorAll('input[name^="shop_counts"]'))
                                        .reduce((sum, input) => sum + parseInt(input.value) || 0, 0);
                
                if (totalCount > (duration * orderPerDay)) {
                    errorText.textContent = 'The total count exceeds the duration in days.';
                    errorMessageDiv.style.display = 'block';
                    input.value = ''; // Optionally clear the input or handle as needed
                } else {
                    errorMessageDiv.style.display = 'none'; // Hide error message if valid
                }
            });
        });
    }

    function updateShopCounts(shopCounts = {}) {
        const selectedShops = Array.from(document.getElementById('edit-shop').selectedOptions).map(option => ({
            id: option.value,
            name: option.getAttribute('data-name')
        }));
        
        const shopCountsDiv = document.getElementById('edit-shop-counts');
        shopCountsDiv.innerHTML = ''; // Clear existing counts

        selectedShops.forEach(shop => {
            const countField = document.createElement('div');
            countField.classList.add('mb-3');
            
            countField.innerHTML = `
                <label for="count-${shop.id}">Count for ${shop.name}</label>
                <input type="number" name="shop_counts[${shop.id}]" id="count-${shop.id}" class="form-control" min="0" value="${shopCounts[shop.id] || ''}">
            `;
            
            shopCountsDiv.appendChild(countField);
        });
    }

</script>


@endsection