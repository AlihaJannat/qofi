@extends('layout.adminapp')
@section('content')
@php
$superAdmin = auth('admin')->user()->id == 1;
$canCreate = auth('admin')->user()->roleRel?->permissions->contains('name', 'order_create') || $superAdmin;
$canEdit = auth('admin')->user()->roleRel?->permissions->contains('name', 'order_edit') || $superAdmin;
$canDelete = auth('admin')->user()->roleRel?->permissions->contains('name', 'order_delete') || $superAdmin;
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
            <h5 class="card-header">Order</h5>
            {{-- <select id="statusFilter" class="mx-3">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="block">Block</option>
            </select> --}}
        </div>
        <div class="dt-action-buttons text-end pt-3 pt-md-0">
            <div class="dt-buttons btn-group flex-wrap">
                @if ($canCreate)
                <button data-bs-toggle="modal" class="btn create-new btn-primary mx-3" data-bs-target="#orderModal"
                    type="button"><span><i class="bx bx-plus me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New
                            Order</span></span></button>
                @endif
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-row-grouping table table-bordered" id="orderTable">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Add Order</h3>
                </div>

                <form class="add-new-order pt-0" onsubmit="addOrder(event, this)"
                    action="{{ route('admin.order.add') }}">

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


{{-- edit order --}}
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Edit Order</h3>
                </div>
                <form class="add-new-order pt-0" onsubmit="updateOrder(event, this)"
                    action="{{ route('admin.order.update') }}">
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
    $('#orderModal').on('shown.bs.modal', function () {
            $('.shop-select').select2({
                placeholder: "Select Shops",
                width: '100%'
            });
        });
        $('#editOrderModal').on('shown.bs.modal', function () {
            $('.shop-select-edit').select2({
                dropdownParent: $('#editOrderModal'),
                width: '100%' // Adjust based on your layout
            });
        });


        var deleteOrder
        var addOrder
        var editOrder
        var showShops
        $(document).ready(function() {
            var table = $('#orderTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.order.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [
                    {
                        data: 'customer name',
                        name: 'customer name',
                    },
                    {
                        data: 'customer email',
                        name: 'customer email',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data) {
                            html = `{{app_setting('site_currency')}} `+data
                            return html
                        },
                    },
                    {
                        data: 'order_status',
                        name: 'order_status',
                    },
                    {
                        data: 'payment status',
                        name: 'payment status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    },
                ]
            })


            $('#statusFilter').on('change', function() {
                table.ajax.reload(); // Reload the DataTable when the select field changes
            });


            deleteOrder = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Order, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.order.delete') }}",
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

            
        });
</script>


<script>
    function addCount(selectElement) {
        const selectedOptions = Array.from(selectElement.selectedOptions);
        const shopCountsDiv = document.getElementById('shop-counts');
        const duration = parseInt(document.getElementById('duration').value) || 0; // Get the order duration
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