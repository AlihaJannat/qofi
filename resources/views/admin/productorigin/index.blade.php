@extends('layout.adminapp')
@section('content')
@php
$superAdmin = auth('admin')->user()->id == 1;
$canCreate = auth('admin')->user()->roleRel?->permissions->contains('name', 'origin_create') || $superAdmin;
$canEdit = auth('admin')->user()->roleRel?->permissions->contains('name', 'origin_edit') || $superAdmin;
$canDelete = auth('admin')->user()->roleRel?->permissions->contains('name', 'origin_delete') || $superAdmin;
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-header">Product Origin</h5>
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
                    data-bs-target="#productoriginModal" type="button"><span><i class="bx bx-plus me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New
                            Product Origin</span></span></button>
                @endif
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-row-grouping table table-bordered" id="productoriginTable">
                <thead>
                    <tr>
                        <th>Origin</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="productoriginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Add Product Origin</h3>
                </div>

                <form class="add-new-productorigin pt-0" onsubmit="addProductorigin(event, this)"
                    action="{{ route('admin.productorigin.add') }}">

                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Origin</label>
                        <input type="text" class="form-control" name="title" id="title" required />
                    </div>
                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>



{{-- edit productorigin --}}
<div class="modal fade" id="editProductoriginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Edit Product Origin</h3>
                </div>
                <form class="add-new-productorigin pt-0" onsubmit="updateProductOrigin(event, this)"
                    action="{{ route('admin.productorigin.update') }}">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Origin</label>
                        <input type="text" class="form-control" name="title" id="edit-title" required />
                    </div>

                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Save</button>
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
    @if ($canEdit)
            //changing the status
            function statusChange(itSelf, id) {
                var button = $(itSelf);
                if (button.hasClass('active')) {
                    Swal.fire({
                            title: `Are you sure you want to mark this Origin as Block?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.productorigin.status') }}",
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
                            title: `Are you sure you want to mark this Origin as Active?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.productorigin.status') }}",
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

        var deleteProductOrigin
        var addProductOrigin
        var editProductOrigin
        var updateProductOrigin
        $(document).ready(function() {
            var table = $('#productoriginTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.productorigin.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [
                    {
                        data: 'title',
                        name: 'title',
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

            addProductorigin = function(e, form) {
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

            editProductOrigin = function(origin, id) {
                title = origin.title
                

                $('#edit-id').val(id)
                $('#edit-title').val(title)

                $('#editProductoriginModal').modal('show')
            }

            updateProductOrigin = function(e, form) {
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

            deleteProductorigin = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Productorigin, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.productorigin.delete') }}",
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


@endsection