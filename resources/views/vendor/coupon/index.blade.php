@extends('layout.vendorapp')
@section('content')
    @php
        $vendor = auth('vendor')->user();
        $isOwner = $vendor->isOwner();
        $canCreate = $vendor->roleRel?->permissions->contains('name', 'mycoupon_create') || $isOwner;
        $canEdit = $vendor->roleRel?->permissions->contains('name', 'mycoupon_edit') || $isOwner;
        $canDelete = $vendor->roleRel?->permissions->contains('name', 'mycoupon_delete') || $isOwner;
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">Coupons</h5>
                {{-- <select id="statusFilter" class="mx-3">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="block">Block</option>
                </select> --}}
            </div>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    @if ($canCreate)
                        <button data-bs-toggle="modal" class="btn create-new btn-primary mx-3" data-bs-target="#couponModal"
                            type="button"><span><i class="bx bx-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Add New
                                    Coupon</span></span></button>
                    @endif
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="couponsTable">
                    <thead>
                        <tr>
                            <th>Expire At</th>
                            <th>Coupon Code</th>
                            <th>Usage</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="couponModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Add Coupon</h3>
                    </div>
                    <form class="add-new-coupon pt-0" onsubmit="addCoupon(event, this)"
                        action="{{ route('vendor.coupon.add') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" name="code" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Discount</label>
                            <div class="d-flex">
                                <div class="flex-grow-1 me-2">
                                    <input type="number" class="form-control" name="value" required />
                                </div>
                                <div>
                                    <select name="type" class="form-select">
                                        <option value="percent">%</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Limit(keep null for no limit)</label>
                            <input type="number" class="form-control" name="max_limit" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expiry</label>
                            <input type="date" class="form-control" name="expired_at" required />
                        </div>
                        <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit coupon --}}
    <div class="modal fade" id="editCouponModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Edit Coupon</h3>
                    </div>
                    <form class="add-new-coupon pt-0" onsubmit="updateCoupon(event, this)"
                        action="{{ route('vendor.coupon.update') }}">
                        @csrf
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" name="code" id="edit-code" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Discount</label>
                            <div class="d-flex">
                                <div class="flex-grow-1 me-2">
                                    <input type="number" class="form-control" name="value" id="edit-value" required />
                                </div>
                                <div>
                                    <select name="type" class="form-select" id="edit-type">
                                        <option value="percent">%</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Limit(keep null for no limit)</label>
                            <input type="number" class="form-control" name="max_limit" id="edit-max_limit" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expiry</label>
                            <input type="date" class="form-control" name="expired_at" id="edit-expired_at" required />
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
        @if ($canEdit)
            //changing the status
            function statusChange(itSelf, id) {
                var button = $(itSelf);
                if (button.hasClass('active')) {
                    Swal.fire({
                            title: `Are you sure you want to mark this Coupon as Block?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('vendor.coupon.status') }}",
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
                            title: `Are you sure you want to mark this Coupon as Active?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('vendor.coupon.status') }}",
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

        var deleteCoupon
        var addCoupon
        var editCoupon
        $(document).ready(function() {
            var table = $('#couponsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vendor.coupon.yajra', $vendor->sw_shop_id) }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [
                    {
                        data: 'expired_at',
                        name: 'expired_at'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'use_count',
                        name: 'use_count',
                        render: function(data,type,row){
                            if (!row.max_limit) {
                                return data
                            } else {
                                return data+"/"+row.max_limit
                            }
                        }
                    },
                    {
                        data: 'value',
                        name: 'value',
                        render: function(data,type,row){
                            if (row.type == 'fixed') {
                                return data
                            } else {
                                return data+"%"
                            }
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

            addCoupon = function(e, form) {
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

            editCoupon = function(btn, id) {
                code = $(btn).data('code')
                type = $(btn).data('type')
                value = $(btn).data('value')
                max_limit = $(btn).data('max_limit')
                expired_at = $(btn).data('expired_at')

                $('#edit-id').val(id)
                $('#edit-code').val(code)
                $('#edit-type').val(type)
                $('#edit-value').val(value)
                $('#edit-max_limit').val(max_limit)
                $('#edit-expired_at').val(expired_at)

                $('#editCouponModal').modal('show')
            }

            updateCoupon = function(e, form) {
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

            deleteCoupon = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Coupon, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.coupon.delete') }}",
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
