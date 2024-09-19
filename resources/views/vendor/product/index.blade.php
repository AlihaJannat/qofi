@extends('layout.vendorapp')
@section('content')
    <style>
        /* tbody td:nth-child(2),
                th:nth-child(2) {
                    display: none;
                } */

        .stock-span {
            padding: 5px;
            border-radius: 7px;
            color: aliceblue;
        }
    </style>
    @php
        $vendor = auth('vendor')->user();
        $isOwner = $vendor->isOwner();
        $canCreate = $vendor->roleRel?->permissions->contains('name', 'product_create') || $isOwner;
        $canEdit = $vendor->roleRel?->permissions->contains('name', 'product_edit') || $isOwner;
        $canDelete = $vendor->roleRel?->permissions->contains('name', 'product_delete') || $isOwner;
        $productHeight = $vendor->roleRel?->permissions->contains('name', 'product_height') || $isOwner;
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">Products</h5>
                <select id="statusFilter" class="mx-3">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="inActive">InActive</option>
                </select>
            </div>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    @if ($canCreate)
                        <a class="btn create-new btn-primary mx-3" href="{{ route('vendor.product.create') }}"><span><i
                                    class="bx bx-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Add New
                                    Product</span></span></a>
                    @endif
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="productTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Country</th>
                            {{-- <th>Default Height</th>
                            <th>Height</th> --}}
                            <th>Created At</th>
                            <th>Stock Status</th>
                            <th>Status</th>
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
        //changing the status
        function statusChange(itSelf, id) {
            var button = $(itSelf);
            if (button.hasClass('active')) {
                Swal.fire({
                        title: `Are you sure you want to mark this Shop Product as Block?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.product.status') }}",
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
                        title: `Are you sure you want to mark this Shop Product as Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.product.status') }}",
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

        var deleteProduct
        $(document).ready(function() {
            var table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vendor.product.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.shop_id = {{ $vendor->sw_shop_id ?: 0 }};
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                        d.productHeight = {{ $productHeight ?: 0 }};
                    }
                },
                columns: [{
                        data: 'product',
                        name: 'name'
                    },
                    {
                        data: 'countries.name',
                        name: 'countries.name'
                    },
                    // {
                    //     data: 'default_height',
                    //     name: 'default_height',
                    //     render: function(data) {
                    //         if (!data) {
                    //             return "N/A";
                    //         }

                    //         html = data.value + " " + (data.unit ? data.unit.unit : 'unit') + " (" +
                    //             data.price + " KD)"
                    //         return html
                    //     },
                    //     searchable: false,
                    //     sortable: false
                    // },
                    // {
                    //     data: 'height',
                    //     name: 'height',
                    //     searchable: false,
                    //     sortable: false
                    // },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format(
                                'YYYY-MM-DD'); // Adjust the format as needed
                        }
                    },
                    {
                        data: 'in_stock',
                        name: 'in_stock',
                        render: function(data) {
                            if (data) {
                                html = '<span class="bg-success stock-span">InStock</span>'
                                return html
                            } else {
                                html = '<span class="bg-danger stock-span">OutStock</span>'
                                return html
                            }
                        },
                        searchable: false,
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false,
                    },
                ]
            })

            $('#statusFilter').on('change', function() {
                table.ajax.reload(); // Reload the DataTable when the select field changes
            });


            deleteProduct = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.product.delete') }}",
                                method: "GET",
                                data: {
                                    id: id
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
@endsection
