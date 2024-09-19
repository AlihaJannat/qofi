@extends('layout.adminapp')
@section('content')
    <style>
        .stock-span {
            padding: 5px;
            border-radius: 7px;
            color: aliceblue;
        }

        #shopUserTable td:nth-child(2),
        #shopUserTable td:nth-child(3),
        #shopUserTable th:nth-child(2),
        #shopUserTable th:nth-child(3) {
            display: none;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <a href="{{ route('admin.shop.index') }}" class="text-muted fw-light">Shops /</a> Edit
        </h4>
        <div class="row gy-4">
            @if (Session::has('status'))
                <div class="alert alert-success">

                    {{ Session::get('status') }}

                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">

                    {{ Session::get('error') }}

                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class=" d-flex align-items-center flex-column">
                                <img class="rounded my-4"
                                    src="{{ filter_var($shop->image_name, FILTER_VALIDATE_URL) ? $shop->image_name : asset('images' . $shop->image_name) }}"
                                    height="100" width="100" alt="User avatar" />
                                <div class="user-info text-center">
                                    <h5 class="mb-2">{{ $shop->name }}</h5>
                                    {{-- <span class="badge bg-label-secondary">Employee</span> --}}
                                </div>
                            </div>
                        </div>
                        {{-- <div class="d-flex justify-content-around flex-wrap my-4 py-3">
                            <div class="d-flex align-items-start me-4 mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded"><i class='bx bx-check bx-sm'></i></span>
                                <div>
                                    <h5 class="mb-0">{{ $employee->completed_contracts_count }}</h5>
                                    <span>Project(s) Done</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded"><i
                                        class='bx bx-customize bx-sm'></i></span>
                                <div>
                                    <h5 class="mb-0">{{ $employee->contracts_count }}</h5>
                                    <span>Project(s) Assigned</span>
                                </div>
                            </div>
                        </div> --}}
                        {{-- @php
                            $percentage = 100;
                            if ($employee->contracts_count) {
                                $percentage = ($employee->completed_contracts_count / $employee->contracts_count) * 100;
                            }
                            $percentage = number_format($percentage, 2);
                            if ($percentage < 40) {
                                $status = 'danger';
                            } elseif ($percentage > 40 && $percentage < 85) {
                                $status = 'warning';
                            } else {
                                $status = 'success';
                            }
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0"></h6>
                            <h6 class="mb-0">{{ $percentage }}% Completed</h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $status }}" role="progressbar"
                                style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div> --}}
                        <h5 class="pb-2 border-bottom my-4">Details</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Name:</span>
                                    <span>{{ $shop->name }}</span>
                                </li>
                                
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Owner:</span>
                                    <span>{{ $shop->owner?->email }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Status:</span>
                                    @if ($shop->status)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Blocked</span>
                                    @endif
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Owner Contact:</span>
                                    <span>+(965) {{ $shop->owner?->phone_no }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">Shop Created At:</span>
                                    <span>{{ $shop->created_at->format('Y-m-d') }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">State:</span>
                                    <span>{{ $shop->state?->name }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">City:</span>
                                    <span>{{ $shop->city?->name }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-3">
                                <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#editShop"
                                    data-bs-toggle="modal">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">

                <!--/ Change Password -->
                <div class="card">
                    {{-- <h5 class="card-header">Discounts <button data-bs-target="#addDiscount" data-bs-toggle="modal"
                            class="btn btn-primary">Add Discount</button></h5> --}}
                    <div class="card-datatable table-responsive">
                        <table class="table datatable-invoice border-top" id="shopDiscountTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Default Height</th>
                                    <th>Stock</th>
                                    <th>Is Featured</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="row gy-4">

            <div class="col-xl-6 col-lg-6 col-md-6 order-1 order-md-0">
                <!-- User Card -->
                <div class="card">
                    <h5 class="card-header">Coupons</h5>
                    <div class="card-datatable table-responsive">
                        <table class="table datatable-invoice border-top" id="shopCouponTable">
                            <thead>
                                <tr>
                                    <th>Expire At</th>
                                    <th>Coupon Code</th>
                                    <th>Usage</th>
                                    <th>Discount</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /User Card -->
            </div>

            <!-- User Content -->
            <div class="col-xl-6 col-lg-6 col-md-6 order-0 order-md-1">

                <!--/ Change Password -->
                <div class="card">
                    <h5 class="card-header">Users</h5>
                    <div class="card-datatable table-responsive">
                        <table class="table datatable-invoice border-top" id="shopUserTable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Joining Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Edit User Modal -->
    <div class="modal fade" id="editShop" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Edit Shop Information</h3>
                    </div>
                    <div class="d-flex align-items-start align-items-sm-center gap-4 mb-5">
                        <img src="{{ filter_var($shop->image_name, FILTER_VALIDATE_URL) ? $shop->image_name : asset('images' . $shop->image_name) }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/user/default.jpg') }}';"
                            alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" form="edit-form" class="account-file-input"
                                    name="image" onchange="checkImage(this)" hidden />
                            </label>
                        </div>
                    </div>
                    <form action="{{ route('admin.shop.edit', ['shop_id' => $shop->id]) }}" id="edit-form"
                        enctype="multipart/form-data" class="row g-3" method="POST">
                        @csrf
                        <!-- Account Details -->

                        <div class="col-12">
                            <h6 class="fw-normal">Shop Details</h6>
                            <hr class="mt-0" />
                        </div>


                        <div class="col-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ $shop->name }}" name="name"
                                    placeholder="Enter Name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Owner</label>
                                <select name="owner_id" class="form-select" readonly required>
                                    @if ($shop->owner)
                                        <option value="{{ $shop->owner_id }}" selected>{{ $shop->owner->email }}</option>
                                    @else
                                        <option value="" selected hidden>--select owner--</option>
                                    @endif
                                    @foreach ($shopOwners as $owner)
                                        <option value="{{ $owner->id }}">
                                            {{ $owner->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State</label>
                                <select name="state_id" class="form-select" required onchange="getCities(this.value)">
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ $state->id == $shop->state_id ? 'selected' : '' }}>
                                            {{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <select name="city_id" id="city_id" class="form-select" required>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ $city->id == $shop->city_id ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="number" step="0.0000001" name="latitude" class="form-control" required
                                    value="{{ $shop->latitude }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="number" step="0.0000001" name="longitude" class="form-control" required
                                    value="{{ $shop->longitude }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admindic/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/flatpickr/flatpickr.js') }}"></script>

    <script>
        function checkImage(input) {
            var file = input.files[0];
            isValid = validateFile(file, 2, true)
            if (isValid) {
                // Get the URL of the selected file and set it as the src attribute of the image tag
                var imagePreview = document.getElementById("uploadedAvatar");
                var fileURL = URL.createObjectURL(file);
                imagePreview.src = fileURL;
            } else {
                input.value = ''; // Clear the file input if the file is not valid
                // You may also want to display an error message here
            }
        }

        function verifyFile(input) {
            var file = input.files[0];
            isValid = validateFile(file, 2)
            if (!isValid) {
                input.value = '';
            }
        }

        function copyToken(token) {
            navigator.clipboard.writeText(token)

            Swal.fire({
                title: "Token Copied",
                icon: 'success',
                timer: 1000, // Duration in milliseconds
                showConfirmButton: false // Hide the confirm button
            })
        }

        //changing the featured
        function statusFeatured(itSelf, id) {
            var button = $(itSelf);
            if (button.hasClass('active')) {
                Swal.fire({
                        title: `Are you sure you want to mark this Shop Product as Block?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.shop.product.featured') }}",
                                method: "GET",
                                data: {
                                    id: id,
                                    status: 0
                                },
                                success: function(data) {
                                    button.addClass('btn-warning');
                                    button.addClass('block');
                                    button.html('Not Featured');
                                    button.removeClass('active');
                                    button.removeClass('btn-success');
                                }
                            })
                        }
                    })
            } else if (button.hasClass('block')) {
                Swal.fire({
                        title: `Are you sure you want to mark this Shop Product as Featured?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.shop.product.featured') }}",
                                method: "GET",
                                data: {
                                    id: id,
                                    status: 1
                                },
                                success: function(data) {
                                    button.removeClass('btn-warning');
                                    button.removeClass('block');
                                    button.html('Featured');
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

        var getCities
        $(document).ready(function() {
            var table = $('#shopDiscountTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.shop.product', $shop->id) }}",
                columns: [{
                        data: 'product',
                        name: 'name',
                    },
                    {
                        data: 'default_height',
                        name: 'default_height',
                        render: function(data) {
                            if (!data) {
                                return "N/A";
                            }

                            html = data.value + " " + (data.unit ? data.unit.unit : 'unit') + " (" +
                                data.price + " KD)"
                            return html
                        },
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'in_stock',
                        name: 'in_stock',
                        render: function(data) {
                            if (data) {
                                html = '<span class="bg-success stock-span">InStock</span>'
                                return html
                            } else {
                                html = '<span class="bg-danger stock-span">OutStock</span>'
                                return html
                            }
                        },
                        searchable: false,
                    },
                    {
                        data: 'featured',
                        name: 'featured',
                        searchable: false,
                    },
                ]
            })

            var userTable = $('#shopUserTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vendor.user.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.shop_id = {{ $shop->id }};
                    }
                },
                columns: [{
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
                        data: 'role_rel.name',
                        name: 'roleRel.name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no',
                        render: function(data) {
                            return '+(965) ' + data
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format(
                                'YYYY-MM-DD'); // Adjust the format as needed
                        }
                    },
                ]
            })
            var couponTable = $('#shopCouponTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vendor.coupon.yajra', $shop->id) }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.shop_id = {{ $shop->id }};
                    }
                },
                columns: [{
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
                        render: function(data, type, row) {
                            if (!row.max_limit) {
                                return data
                            } else {
                                return data + "/" + row.max_limit
                            }
                        }
                    },
                    {
                        data: 'value',
                        name: 'value',
                        render: function(data, type, row) {
                            if (row.type == 'fixed') {
                                return data
                            } else {
                                return data + "%"
                            }
                        }
                    },
                ]
            })

            getCities = function(state_id) {
                $.ajax({
                    url: "{{ route('admin.location.get.cities') }}",
                    method: "get",
                    data: {
                        state_id,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $("#city_id").empty();
                    },

                    success: function(data) {
                        if (data.cities) {
                            data.cities.map((city) => {
                                $("#city_id").append('<option value="' + city.id + '">' +
                                    city.name +
                                    '</option>');
                            })
                        }
                    }
                });
            }
        })
    </script>
@endsection
