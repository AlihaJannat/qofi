@extends('layout.vendorapp')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">Roles & Permission</h5>
            </div>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    <button data-bs-toggle="modal" class="btn create-new btn-primary mx-3" data-bs-target="#roleModal"
                        type="button"><span><i class="bx bx-plus me-sm-1"></i>
                            <span class="d-none d-sm-inline-block">Add New
                                Role</span></span></button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="usersTable">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Add Role</h3>
                    </div>
                    <form class="add-new-role pt-0" onsubmit="addRole(event, this)" action="{{ route('vendor.role.add') }}">
                        @csrf
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="form-group">
                                    <label class="form-label">Name*</label>
                                    <input required type="text" name="name" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12">
                                <h5>Role Permissions</h5>
                                <!-- Permission table -->
                                <div class="table-responsive">
                                    <table class="table table-flush-spacing">
                                        <tbody>
                                            <tr>
                                                <td class="text-nowrap">Administrator Access <i
                                                        class="bx bx-info-circle bx-xs" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        aria-label="Allows a full access to the system"
                                                        data-bs-original-title="Allows a full access to the system"></i>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" onclick="reflectAll(this)"
                                                            type="checkbox" id="selectAll">
                                                        <label class="form-check-label" for="selectAll">
                                                            Select All
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @foreach ($permissions as $key => $permission_module)
                                                <tr>
                                                    <td class="text-nowrap text-capitalize">{{ $key }}</td>
                                                    @foreach ($permission_module as $permission)
                                                        <td>
                                                            <div class="d-flex">
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        onclick="permissionCheck(this, '{{ $permission->name }}');"
                                                                        value="{{ $permission->id }}"
                                                                        data-permission="{{ $permission->name }}"
                                                                        name="permissions[]" id="{{ $permission }}">
                                                                    <label class="form-check-label text-capitalize"
                                                                        for="{{ $permission }}">
                                                                        {{ str_replace('_', ' ', $permission->name) }}?
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Permission table -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit role --}}
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Edit Role</h3>
                    </div>
                    <form class="add-new-role pt-0" onsubmit="updateRole(event, this)" id="edit-modal-body"
                        action="{{ route('vendor.role.edit') }}">

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var deleteRole
        var addRole
        var getDetails
        var permissionCheck
        var reflectAll
        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vendor.role.yajra') }}",
                    data: function(d) {
                        d.shop_id = {{auth('vendor')->user()->sw_shop_id?:0}};
                    }
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    },
                ]
            })

            addRole = function(e, form) {
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

            getDetails = function(id) {
                $('#editRoleModal').modal('show');
                $.ajax({
                    url: "{{ route('vendor.role.getDetails') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    beforeSend: function() {
                        $('#edit-modal-body').html('<h5>Loading...</h5>')
                    },
                    success: function(data) {
                        $('#edit-modal-body').html(data.html)
                    }
                });
            }

            reflectAll = function(checkbox) {
                if ($(checkbox).is(':checked')) {
                    $('.form-check-input').prop('checked', true);
                } else {
                    $('.form-check-input').prop('checked', false);
                }
            }

            permissionCheck = function(itSelf, permission) {
                permissionArray = permission.split('_')
                array_length = permissionArray.length
                permissionSuffix = permissionArray.slice(0, array_length - 1).join('_');
                permissionPostfix = permissionArray[array_length - 1]
                dependentArray = ['edit', 'create', 'delete', 'height']
                // if checkbox is checked then we check is current permission is child like edit, create or delete these will be dependent on view
                if ($(itSelf).is(':checked')) {
                    if (dependentArray.includes(permissionPostfix)) {
                        parentPermission = permissionSuffix + '_view'
                        const parentCheckbox = $('input[data-permission=' + parentPermission + ']');

                        if (!parentCheckbox.is(':checked')) {
                            Swal.fire({
                                text: "Selected permission is dependent on its viewability, Do you want to enable it?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Save",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // if allowed then checking its parent permission
                                    parentCheckbox.prop('checked', true);
                                } else {
                                    // else uncheck the current permission
                                    $(itSelf).prop('checked', false)
                                }
                            })
                        }

                    }
                } else {
                    // else checking if view permission is unchecked so unchecked its all child permissions
                    if (permissionPostfix === 'view') {
                        isChildChecked = false;
                        for (let index = 0; index < dependentArray.length; index++) {
                            const element = dependentArray[index];
                            childPermission = permissionSuffix + '_' + element
                            const childCheckbox = $('input[data-permission=' + childPermission + ']');
                            if (childCheckbox.is(':checked')) {
                                isChildChecked = true;
                                break;
                            }
                        }
                        if (isChildChecked) {
                            Swal.fire({
                                text: "Selected permission has its depending permissions, Do you want to disabled them?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Save",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // if allowed then unchecking its depending permission
                                    for (let index = 0; index < dependentArray.length; index++) {
                                        const element = dependentArray[index];
                                        childPermission = permissionSuffix + '_' + element
                                        const childCheckbox = $('input[data-permission=' +
                                            childPermission +
                                            ']');
                                        childCheckbox.prop('checked', false);
                                    }
                                } else {
                                    // else again checking the current permission
                                    $(itSelf).prop('checked', true)
                                }
                            })
                        }
                    }
                }
            }

            updateRole = function(e, form) {
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

            deleteRole = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Role, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.role.delete') }}",
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
