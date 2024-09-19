@extends('layout.adminapp')
@section('content')
    <style>
        tbody td:nth-child(2), tbody td:nth-child(3),
        th:nth-child(2), th:nth-child(3) {
            display: none;
        }
        tbody td:nth-child(5),
        th:nth-child(5) {
            text-transform: capitalize
        }
        tbody td:nth-child(8),
        th:nth-child(8) {
            text-transform: capitalize
        }
    </style>
    @php
        $superAdmin = auth('admin')->user()->id == 1;
        $canEdit = auth('admin')->user()->roleRel?->permissions->contains('name', 'student_edit') || $superAdmin;
        $canDelete = auth('admin')->user()->roleRel?->permissions->contains('name', 'student_delete') || $superAdmin;
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">Students</h5>
                <select id="statusFilter" class="mx-3">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="block">Block</option>
                </select>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="usersTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>DOB</th>
                            <th>Gender</th>
                            <th>Joining Date</th>
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
                            title: `Are you sure you want to mark this User as Block?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.user.status') }}",
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
                            title: `Are you sure you want to mark this User as Active?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.user.status') }}",
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
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.user.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val(); 
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        render: function(data) {
                            return '+(965) ' + data
                        }
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format(
                                'YYYY-MM-DD'); // Adjust the format as needed
                        }
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
                        text: "If you delete this user, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.user.delete') }}",
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
