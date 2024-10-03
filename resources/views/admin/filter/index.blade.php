@extends('layout.adminapp')
@section('content')
    <style>
        table {
            text-transform: capitalize;
        }

        tbody td:nth-child(3), tbody td:nth-child(1),
        thead th:nth-child(3), thead th:nth-child(1) {
            display: none;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <a href="{{ route('admin.filter.new') }}" class="btn btn-secondary create-new btn-primary" tabindex="0"
                            aria-controls="DataTables_Table_0" type="button"><span><i class="bx bx-plus me-sm-1"></i> <span
                                    class="d-none d-sm-inline-block">Add New Record</span></span></a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="filterTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Image</th>
                            <th>Name </th>
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
        function statusChange(itSelf, id) {
            var button = $(itSelf);

            if (button.hasClass('active')) {
                Swal.fire({
                    title: `Are you sure you want to mark this Filter as Blocked?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateCouponStatus(id, 0, button);
                    }
                });
            } else if (button.hasClass('block')) {
                Swal.fire({
                    title: `Are you sure you want to mark this Filter as Active?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateCouponStatus(id, 1, button);
                    }
                });
            } else {
                Swal.fire({
                    title: `something went wrong please try later`,
                    icon: "warning",
                });
            }
        }

        function updateCouponStatus(id, status, button) {
            $.ajax({
                url: "{{ route('admin.filter.status') }}",
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


        var deleteFilter
        $(document).ready(function() {
            var table = $('#filterTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.filter.yajra') }}",
                columns: [
                    {
                        data: 'sort_order',
                        name: 'sort_order'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
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

            deleteFilter = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Filter, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.filter.delete') }}",
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success: function(data) {
                                    RowToRemove
                                        .remove()
                                        .draw();
                                }
                            })
                        }
                    })
            }

        });
    </script>
@endsection
