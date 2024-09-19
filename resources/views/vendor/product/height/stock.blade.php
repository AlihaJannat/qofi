@extends('layout.vendorapp')
@section('content')
    <style>
        table {
            text-transform: capitalize;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <a href="{{ route('vendor.product.index') }}" class="text-muted fw-light">Products /</a><a
                href="{{ route('vendor.product.height.index',$height->product?->id) }}" class="text-muted fw-light"> Heights /</a> Stock
        </h4>
        <div class="card">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label">
                    <h5 class="card-title mb-0">Stock History ({{ $height->product?->name }}
                        {{ $height->value . ' ' . $height->unit?->unit }})</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <button class="btn btn-secondary add-new btn-primary" tabindex="0"
                            aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasAddStock"><span><i class="bx bx-plus me-0 me-lg-2"></i><span
                                    class="d-none d-lg-inline-block">Add New Stock</span></span></button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="stocksTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddStock" aria-labelledby="offcanvasAddStockLabel">
        <div class="offcanvas-header border-bottom">
            <h6 id="offcanvasAddStockLabel" class="offcanvas-title">Add Stock</h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
            <form class="add-new-stock pt-0" id="addNewUserForm" onsubmit="return false">
                <div class="mb-3">
                    <label class="form-label" for="qty">Quantity</label>
                    <input type="number" id="qty" class="form-control" name="qty" />
                </div>
                <div class="mb-4">
                    <label class="form-label" for="type">Status</label>
                    <select name="type" id="type" class="form-select">
                        <option value="in">In</option>
                        <option value="out">Out</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Description(Short)</label>
                    <input type="text" id="description" class="form-control" name="description">
                </div>
                <button class="btn btn-primary me-sm-3 me-1 data-submit" onclick="addStock()">Add</button>
                <button type="reset" class="btn btn-label-secondary reset-btn" data-bs-dismiss="offcanvas">Cancel</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var addStock
        var deleteStock
        $(document).ready(function() {
            var table = $('#stocksTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('vendor.product.height.stock.yajra', [$height->id]) }}",
                columns: [{
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format(
                                'YYYY-MM-DD HH:mm:ss'); // Adjust the format as needed
                        }
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },

                ]
            })

            addStock = function() {
                $.ajax({
                    url: "{{ route('vendor.product.height.stock.add', [$height->id]) }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        qty: $('#qty').val(),
                        type: $('#type').val(),
                        description: $('#description').val()
                    },
                    success: function(data) {
                        table.draw(false)
                        $('.reset-btn').click()
                    },
                    error: function(data) {
                        console.log(data)
                        if (data.responseJSON?.message) {
                            Swal.fire({
                                title: data.responseJSON?.message,
                                icon: "warning",
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                            })
                            return false;
                        }
                        Swal.fire({
                            title: `Something went wrong`,
                            icon: "warning",
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                        })
                    }
                })
            }

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
                                url: "{{ route('vendor.product.height.stock.delete') }}",
                                method: "DELETE",
                                data: {
                                    id: id,
                                    _token: "{{ csrf_token() }}"
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
