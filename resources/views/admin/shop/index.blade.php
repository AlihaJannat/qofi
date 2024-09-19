@extends('layout.adminapp')
@section('content')
    <style>
        tbody td:nth-child(2),
        th:nth-child(2) {
            display: none;
        }
    </style>
    @php
        $superAdmin = auth('admin')->user()->id == 1;
        $canCreate = auth('admin')->user()->roleRel?->permissions->contains('name', 'shop_create') || $superAdmin;
        $canEdit = auth('admin')->user()->roleRel?->permissions->contains('name', 'shop_edit') || $superAdmin;
        $canDelete = auth('admin')->user()->roleRel?->permissions->contains('name', 'shop_delete') || $superAdmin;
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">Shop</h5>
            </div>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap mx-3">
                    @if ($canCreate)
                        <a href="{{ route('admin.shop.new') }}" class="btn create-new btn-primary"><span><i
                                    class="bx bx-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Add New Shop</span></span></a>
                    @endif
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="shopsTable">
                    <thead>
                        <tr>
                            <th>Shop</th>
                            <th>Name</th>
                            <th>Owner</th>
                            <th>State</th>
                            <th>City</th>
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
        @if ($canEdit)

            //changing the status
            function statusChange(itSelf, id) {
                var button = $(itSelf);
                if (button.hasClass('active')) {
                    Swal.fire({
                            title: `Are you sure you want to mark this Shop Shop as Block?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.shop.status') }}",
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
                            title: `Are you sure you want to mark this Shop Shop as Active?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.shop.status') }}",
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

        var deleteUser
        $(document).ready(function() {
            var table = $('#shopsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.shop.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val(); 
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [{
                        data: 'shop',
                        name: 'shop'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'owner_email',
                        name: 'sw_vendors.email'
                    },
                    {
                        data: 'states.name',
                        name: 'states.name'
                    },
                    {
                        data: 'cities.name',
                        name: 'cities.name'
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

            $('#statusFilter').on('change', function() {
                table.ajax.reload(); // Reload the DataTable when the select field changes
            });

            deleteUser = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Shop, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.shop.delete') }}",
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
