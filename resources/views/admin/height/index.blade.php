@extends('layout.adminapp')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            Height Units
        </h4>
        <div class="card">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label">
                    <h5 class="card-title mb-0">Height Units</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <button data-bs-toggle="modal" class="btn create-new btn-primary" data-bs-target="#heightModal"
                            type="button"><span><i class="bx bx-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Add New
                                    Unit</span></span></button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="heightTable">
                    <thead>
                        <tr>
                            <th>
                                <a class="nav-link dropdown-toggle" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    Actions
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" id="perform-delete" href="#">
                                            <i class="fa fa-trash"></i>
                                            <span class="align-middle">Bulk Delete</span>
                                        </a>
                                    </li>
                                </ul>
                            </th>
                            <th>Unit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- height modal --}}
    <div class="modal fade" id="heightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Add Unit</h3>
                    </div>
                    <form class="add-new-height pt-0" onsubmit="addHeight(event, this)" action="{{ route('admin.height.add') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control" name="unit" placeholder="cm" />
                        </div>
                        <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editHeightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Edit Height</h3>
                    </div>
                    <form class="pt-0" onsubmit="updateHeight(event, this)" action="{{ route('admin.height.update') }}">
                        @csrf
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <input type="text" id="edit-unit" class="form-control" name="unit"
                                placeholder="cm" />
                        </div>
                        <button class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
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
        var addHeight
        var deleteHeight
        var editHeight
        var updateHeight
        $(document).ready(function() {
            var table = $('#heightTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.height.yajra') }}",
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        seachable: false,
                        sortable: false
                    },
                ]
            })

            editHeight = function(id, unit) {
                $('#edit-id').val(id);
                $('#edit-unit').val(unit);

                $('#editHeightModal').modal('show');
            }

            updateHeight = function(e, itSelf) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(itSelf).attr('action'),
                    method: 'POST',
                    data: new FormData(itSelf),
                    success: function(data) {
                        table.draw(false)
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

            addHeight = function(e, itSelf) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(itSelf).attr('action'),
                    method: 'POST',
                    data: new FormData(itSelf),
                    success: function(data) {
                        table.draw(false)
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

            deleteHeight = function(itSelf, id) {

                var RowToRemove = $(itSelf).parents('tr');
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Height, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.height.delete') }}",
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success: function(data) {
                                    RowToRemove
                                        .remove()
                                }
                            })
                        }
                    })
            }


            $('#perform-delete').click(function() {
                const checkedCheckboxes = $('.bulk-checkbox:checked');

                if (checkedCheckboxes.length === 0) {
                    Swal.fire({
                        title: `No Checkbox Selected`,
                        icon: "warning",
                    })
                    return;
                }

                let heightIds = []

                Swal.fire({
                        title: `Are you sure you want to delete these records?`,
                        text: "If you delete it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed) {
                            checkedCheckboxes.each(function() {
                                heightIds.push($(this).val());
                            });
                            $.ajax({
                                url: "{{ route('admin.height.delete') }}",
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    action: 'bulk',
                                    heightIds
                                },
                                beforeSend: function() {
                                    Swal.fire({
                                        title: 'Loading...',
                                        text: 'Please wait',
                                        icon: 'info',
                                        buttons: false,
                                        closeOnClickOutside: false,
                                        closeOnEsc: false,
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        showCloseButton: false,
                                    });
                                },
                                success: function(data) {
                                    table.draw(false);
                                    Swal.close()
                                },
                                error: function(data) {
                                    table.draw(false);
                                    Swal.close()
                                }
                            })

                        }
                    })
                // Perform your action on the selected checkboxes

            });
        });
    </script>
@endsection
