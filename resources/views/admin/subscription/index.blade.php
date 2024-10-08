@extends('layout.adminapp')
@section('content')
@php
$superAdmin = auth('admin')->user()->id == 1;
$canCreate = auth('admin')->user()->roleRel?->permissions->contains('name', 'subscription_create') || $superAdmin;
$canEdit = auth('admin')->user()->roleRel?->permissions->contains('name', 'subscription_edit') || $superAdmin;
$canDelete = auth('admin')->user()->roleRel?->permissions->contains('name', 'subscription_delete') || $superAdmin;
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-header">Subscription</h5>
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
                    data-bs-target="#subscriptionModal" type="button"><span><i class="bx bx-plus me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New
                            Subscription</span></span></button>
                @endif
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-row-grouping table table-bordered" id="subscriptionTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Short Description</th>
                        <th>Long Description</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Shops</th>
                        <th>Cups</th>
                        <th>Coffee Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Add Subscription</h3>
                </div>

                <form class="add-new-subscription pt-0" onsubmit="addSubscription(event, this)"
                    action="{{ route('admin.plan.subscription.add') }}">

                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small>(optional)</small></label>
                        <textarea class="form-control" name="description" id="description" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (In Days)</label>
                        <input type="number" class="form-control" name="duration" id="duration" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (In {{app_setting('site_currency')}})</label>
                        <input type="text" class="form-control" name="price" id="price" required />
                    </div>



                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}

{{-- add subscription modal --}}
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Add Subscription</h3>
                </div>

                <form class="add-new-subscription pt-0" onsubmit="addSubscription(event, this)"
                    action="{{ route('admin.plan.subscription.add') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required />
                    </div>

                    <!-- Updated Description (Short Description) -->
                    <div class="mb-3">
                        <label class="form-label">Short Description <small>(optional)</small></label>
                        <textarea class="form-control" name="short_description" id="short_description" cols="30"
                            rows="3"></textarea>
                    </div>

                    <!-- New Long Description Field -->
                    <div class="mb-3">
                        <label class="form-label">Long Description <small>(optional)</small></label>
                        <textarea class="form-control" name="long_description" id="long_description" cols="30"
                            rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duration (In Days)</label>
                        <input type="number" class="form-control" name="duration" id="duration" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price (In {{app_setting('site_currency')}})</label>
                        <input type="text" class="form-control" name="price" id="price" required />
                    </div>

                    <!-- New Shops Count Field -->
                    <div class="mb-3">
                        <label class="form-label">Shops Count</label>
                        <input type="number" class="form-control" name="shops_count" id="shops_count" required />
                    </div>

                    <!-- New Cups Count Field -->
                    <div class="mb-3">
                        <label class="form-label">Cups Count</label>
                        <input type="number" class="form-control" name="cups_count" id="cups_count" required />
                    </div>

                    <!-- New Coffee Type Field -->
                    <div class="mb-3">
                        <label class="form-label">Coffee Type (e.g. 4, 4-5, 6-8)</label>
                        <input type="text" class="form-control" name="coffee_type" id="coffee_type" required />
                    </div>

                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- edit subscription --}}
<div class="modal fade" id="editSubscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Edit Subscription</h3>
                </div>

                <form class="add-new-subscription pt-0" onsubmit="updateSubscription(event, this)"
                    action="{{ route('admin.plan.subscription.update') }}">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required />
                    </div>

                    <!-- Updated Description (Short Description) -->
                    <div class="mb-3">
                        <label class="form-label">Short Description <small>(optional)</small></label>
                        <textarea class="form-control" name="short_description" id="edit-short-description" cols="30"
                            rows="3"></textarea>
                    </div>

                    <!-- New Long Description Field -->
                    <div class="mb-3">
                        <label class="form-label">Long Description <small>(optional)</small></label>
                        <textarea class="form-control" name="long_description" id="edit-long-description" cols="30"
                            rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duration (In Days)</label>
                        <input type="number" class="form-control" name="duration" id="edit-duration" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price (In {{ app_setting('site_currency') }})</label>
                        <input type="text" class="form-control" name="price" id="edit-price" required />
                    </div>

                    <!-- New Shops Count Field -->
                    <div class="mb-3">
                        <label class="form-label">Shops Count</label>
                        <input type="number" class="form-control" name="shops_count" id="edit-shops-count" required />
                    </div>

                    <!-- New Cups Count Field -->
                    <div class="mb-3">
                        <label class="form-label">Cups Count</label>
                        <input type="number" class="form-control" name="cups_count" id="edit-cups-count" required />
                    </div>

                    <!-- New Coffee Type Field -->
                    <div class="mb-3">
                        <label class="form-label">Coffee Type (e.g. 4, 4-5, 6-8)</label>
                        <input type="text" class="form-control" name="coffee_type" id="edit-coffee-type" required />
                    </div>

                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="editSubscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Edit Subscription</h3>
                </div>
                <form class="add-new-subscription pt-0" onsubmit="updateSubscription(event, this)"
                    action="{{ route('admin.plan.subscription.update') }}">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <small>(optional)</small></label>
                        <textarea class="form-control" name="description" id="edit-description" cols="30"
                            rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (In Days)</label>
                        <input type="number" class="form-control" name="duration" id="edit-duration" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (In {{app_setting('site_currency')}})</label>
                        <input type="text" class="form-control" name="price" id="edit-price" required />
                    </div>
                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('script')
<script>
    @if ($canEdit)
            //changing the status
            function statusChange(itSelf, id) {
                var button = $(itSelf);
                if (button.hasClass('active')) {
                    Swal.fire({
                            title: `Are you sure you want to mark this Subscription as Block?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.plan.subscription.status') }}",
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
                            title: `Are you sure you want to mark this Subscription as Active?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.plan.subscription.status') }}",
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

        var deleteSubscription
        var addSubscription
        var editSubscription
        $(document).ready(function() {
            var table = $('#subscriptionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.plan.subscription.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.canEdit = {{ $canEdit ?: 0 }};
                        d.canDelete = {{ $canDelete ?: 0 }};
                    }
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'short_description', // Updated from 'description' to 'short_description'
                        name: 'short_description',
                    },
                    {
                        data: 'long_description', // Added long description column
                        name: 'long_description',
                    },
                    {
                        data: 'duration',
                        name: 'duration',
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data) {
                            html = `{{app_setting('site_currency')}} ` + data;
                            return html;
                        },
                    },
                    {
                        data: 'shops_count', // Added shops count column
                        name: 'shops_count',
                    },
                    {
                        data: 'cups_count', // Added cups count column
                        name: 'cups_count',
                    },
                    {
                        data: 'coffee_type', // Added coffee type column
                        name: 'coffee_type',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                    },
                ]

            })
            $('#statusFilter').on('change', function() {
                table.ajax.reload(); // Reload the DataTable when the select field changes
            });

            addSubscription = function(e, form) {
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

            editSubscription = function(sub, id) {
                let name = sub.name;
                let shortDescription = sub.short_description;
                let longDescription = sub.long_description;
                let duration = sub.duration;
                let price = sub.price;
                let shopsCount = sub.shops_count;
                let cupsCount = sub.cups_count;
                let coffeeType = sub.coffee_type;

                // Set values to the form fields
                $('#edit-id').val(id);
                $('#edit-name').val(name);
                $('#edit-short-description').val(shortDescription); 
                $('#edit-long-description').val(longDescription); 
                $('#edit-duration').val(duration);
                $('#edit-price').val(price);
                $('#edit-shops-count').val(shopsCount); 
                $('#edit-cups-count').val(cupsCount); 
                $('#edit-coffee-type').val(coffeeType); 

                // Show the modal
                $('#editSubscriptionModal').modal('show');
            }


            updateSubscription = function(e, form) {
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

            deleteSubscription = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this Subscription, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.plan.subscription.delete') }}",
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