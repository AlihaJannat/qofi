@extends('layout.vendorapp')
@section('content')
@php

$vendor = auth('vendor')->user();
$isOwner = $vendor->isOwner();
$canCreate = $vendor->roleRel?->permissions->contains('name', 'product_create') || $isOwner;
$canEdit = $vendor->roleRel?->permissions->contains('name', 'product_edit') || $isOwner;
$canDelete = $vendor->roleRel?->permissions->contains('name', 'product_delete') || $isOwner;
$productHeight = $vendor->roleRel?->permissions->contains('name', 'product_height') || $isOwner;
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
                    url: "{{ route('vendor.order.yajra') }}",
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
                        data: 'vendor_total',
                        name: 'vendor_total',
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


            
        });
</script>



@endsection