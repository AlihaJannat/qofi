@extends('layout.adminapp')
@section('content')
    @php
        $superAdmin = auth('admin')->user()->id == 1;
        $canCreate =
            auth('admin')->user()->roleRel?->permissions->contains('name', 'shopCategory_create') || $superAdmin;
        $canEdit = auth('admin')->user()->roleRel?->permissions->contains('name', 'shopCategory_edit') || $superAdmin;
        $canDelete =
            auth('admin')->user()->roleRel?->permissions->contains('name', 'shopCategory_delete') || $superAdmin;
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label">
                    <h5 class="card-title mb-0">Categories</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        @if ($canCreate)
                            <a href="{{ route('admin.shop.category.new') }}" class="btn btn-secondary create-new btn-primary"
                                tabindex="0" aria-controls="DataTables_Table_0" type="button"><span><i
                                        class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New
                                        Record</span></span></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="categoryTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
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
            function statusChange(itSelf, id) {
                var button = $(itSelf);

                if (button.hasClass('active')) {
                    Swal.fire({
                        title: `Are you sure you want to mark this Category as Blocked?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateCategoryStatus(id, 0, button);
                        }
                    });
                } else if (button.hasClass('block')) {
                    Swal.fire({
                        title: `Are you sure you want to mark this Category as Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateCategoryStatus(id, 1, button);
                        }
                    });
                } else {
                    Swal.fire({
                        title: `something went wrong please try later`,
                        icon: "warning",
                    });
                }
            }
        @endif

        function updateCategoryStatus(id, status, button) {
            $.ajax({
                url: "{{ route('admin.shop.category.status') }}",
                method: "GET",
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (status === 0) {
                        button.removeClass('btn-success active');
                        button.addClass('btn-warning block');
                        button.html('Block');
                    } else if (status === 1) {
                        button.removeClass('btn-warning block');
                        button.addClass('btn-success active');
                        button.html('Active');
                    }
                }
            });
        }

        var deleteCategory
        $(document).ready(function() {
            var table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.shop.category.yajra') }}",
                    data: function(d) {
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    },
                ]
            })

            deleteCategory = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Category, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.shop.category.delete') }}",
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success: function(data) {
                                    RowToRemove
                                        .remove()
                                        .draw();
                                },
                                error: function(data) {
                                    if (data.responseJSON?.message) {
                                        Swal.fire({
                                            title: `Oops!`,
                                            text: data.responseJSON?.message,
                                            icon: "warning",
                                            confirmButtonText: 'OK',
                                        })
                                        return;
                                    }
                                    Swal.fire({
                                        title: 'Something went wrong',
                                        icon: "error",
                                    })
                                }
                            })
                        }
                    })
            }

        });
    </script>
@endsection
