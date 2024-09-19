@extends('layout.vendorapp')
@section('content')
    <style>
        .stock-span {
            padding: 5px 10px;
            border-radius: 7px;
            color: aliceblue;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <a href="{{ route('vendor.product.index') }}" class="text-muted fw-light">Products /</a> Heights
        </h4>
        <div class="card">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-header">{{ $product->name }} Heights</h5>
            </div>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    <button data-bs-toggle="modal" class="btn create-new btn-primary mx-3" data-bs-target="#heightModal"
                        type="button"><span><i class="bx bx-plus me-sm-1"></i>
                            <span class="d-none d-sm-inline-block">Add New
                                Height</span></span></button>
                </div>
            </div>
            @if (Session::has('status'))
                <div class="alert-hide alert alert-success">

                    {{ Session::get('status') }}

                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert-hide alert alert-danger">

                    {{ Session::get('error') }}

                </div>
            @endif
            @if ($errors->any())
                <div class="alert-hide alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="heightTable">
                    <thead>
                        <tr>
                            <th>Value</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Is Default</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->heights as $height)
                            <tr>
                                <td>{{ $height->value }}</td>
                                <td>{{ $height->unit?->unit }}</td>
                                <td>{{ $height->price }}</td>
                                <td>
                                    @if ($height->is_default)
                                        <span class="bg-success stock-span">Yes</span>
                                    @else
                                        <span class="bg-warning stock-span">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($height->status)
                                        <button class='btn btn-success active'
                                            onclick='statusChange(this,  {{$height->id}} )'>Active</button>
                                    @else
                                        <button class='btn btn-warning block'
                                            onclick='statusChange(this,  {{$height->id}} )'>In-Active</button>
                                    @endif
                                </td>
                                <td>
                                    <div class='d-inline-block'>
                                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow'
                                            data-bs-toggle='dropdown'>
                                            <i class='bx bx-dots-vertical-rounded'></i>
                                        </a>
                                        <div class='dropdown-menu dropdown-menu-end m-0'>
                                            <a onclick="editHeight({{$height->id}}, {{$height->value}}, {{$height->sw_unit_id}}, {{$height->price}}, {{$height->is_default}})" type='button' href='javascript:void(0)'
                                                class='dropdown-item'>Edit</a>
                                            <a href="{{route('vendor.product.height.stock.index',$height->id)}}"
                                                class='dropdown-item'>Stock History</a>
                                            <div class='dropdown-divider'></div>
                                            <a href='javascript:;' onclick='deleteHeight(this,  {{ $height->id }} )'
                                                class='dropdown-item text-danger delete-record'>Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="heightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Add Height</h3>
                    </div>
                    <form class="add-new-height pt-0" method="POST"
                        action="{{ route('vendor.product.height.add', $product->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="number" step="0.01" class="form-control" name="value" placeholder="20.2"
                                required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <select name="sw_unit_id" class="form-select">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" name="price" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Make Default?</label>
                            <select class="form-select" name="is_default">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit height --}}
    <div class="modal fade" id="editHeightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Edit Height</h3>
                    </div>
                    <form class="add-new-height pt-0" method="POST"
                        action="{{ route('vendor.product.height.update', $product->id) }}">
                        @csrf
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="number" id="edit-value" step="0.01" class="form-control" name="value" placeholder="20.2"
                                required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <select name="sw_unit_id" id="edit-unit" class="form-select">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" id="edit-price" class="form-control" name="price" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Make Default?</label>
                            <select class="form-select" id="edit-default" name="is_default">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
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
        //changing the status
        function statusChange(itSelf, id) {
            var button = $(itSelf);
            if (button.hasClass('active')) {
                Swal.fire({
                        title: `Are you sure you want to mark this Shop Height as In-Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.product.height.status', $product->id) }}",
                                method: "POST",
                                data: {
                                    id: id,
                                    status: 0,
                                    _token: "{{csrf_token()}}"
                                },
                                success: function(data) {
                                    button.addClass('btn-warning');
                                    button.addClass('block');
                                    button.html('In-Active');
                                    button.removeClass('active');
                                    button.removeClass('btn-success');
                                }
                            })
                        }
                    })
            } else if (button.hasClass('block')) {
                Swal.fire({
                        title: `Are you sure you want to mark this Shop Height as Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.product.height.status', $product->id) }}",
                                method: "POST",
                                data: {
                                    id: id,
                                    status: 1,
                                    _token: "{{csrf_token()}}"
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

        var deleteHeight
        var editHeight
        $(document).ready(function() {
            var table = $('#heightTable').DataTable()

            editHeight = function(id, value, unit, price, is_default) {
                $('#edit-id').val(id)
                $('#edit-value').val(value)
                $('#edit-unit').val(unit)
                $('#edit-price').val(price)
                $('#edit-default').val(is_default)

                $('#editHeightModal').modal('show')
            }

            deleteHeight = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
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
                                url: "{{ route('vendor.product.height.delete', $product->id) }}",
                                method: "POST",
                                data: {
                                    id: id,
                                    _token: "{{ csrf_token() }}",
                                },
                                success: function(data) {
                                    RowToRemove.remove()
                                },
                                error: function(error) {
                                    console.log(error)
                                    if (error.responseJSON?.message) {
                                        Swal.fire({
                                            title: error.responseJSON.message,
                                            icon: "warning",
                                            showCancelButton: false,
                                            confirmButtonText: 'OK',
                                        })
                                    }
                                    else {
                                        Swal.fire({
                                            title: "something sent wrong",
                                            icon: "warning",
                                            showCancelButton: false,
                                            confirmButtonText: 'OK',
                                        })
                                    }
                                }
                            })
                        }
                    })
            }

            setTimeout(() => {
                $('.alert-hide').fadeOut()
            }, 1500);
        });
    </script>
@endsection
