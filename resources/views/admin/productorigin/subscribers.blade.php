@extends('layout.adminapp')
@section('content')
@php
$superAdmin = auth('admin')->user()->id == 1;
@endphp
<style>
  .select2-container {
    z-index: 9999 !important;
  }

  .modal-body {
    overflow-y: auto;
    max-height: 120vh;
    /* Adjust based on your design */
  }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="d-flex align-items-center justify-content-between">
      <h5 class="card-header">Subscriber</h5>
      {{-- <select id="statusFilter" class="mx-3">
        <option value="all">All</option>
        <option value="active">Active</option>
        <option value="block">Block</option>
      </select> --}}
    </div>
    <div class="card-datatable table-responsive">
      <table class="dt-row-grouping table table-bordered" id="subscriberTable">
        <thead>
          <tr>
            <th>User</th>
            <th>Subscription</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
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
                            title: `Are you sure you want to deactivate this user subscription?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.plan.subscriber.status') }}",
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
                            title: `Are you sure you want to activate this user subscription?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: 'OK',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.plan.subscriber.status') }}",
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
  $(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var subscriptionFilter = urlParams.get('sub'); 

    var query = 'null';
    if(subscriptionFilter){
       query = subscriptionFilter;
    }
            var table = $('#subscriberTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.plan.subscriber.yajra') }}",
                    data: function(d) {
                        d.sub = query;
                    }
                },
                columns: [
                    {
                        data: 'user',
                        name: 'user',
                        orderable: false 
                    },
                    {
                        data: 'subscription',
                        name: 'subscription',
                        orderable: false ,
                    },
                    {
                      data: 'amount',
                      name: 'amount',
                      render: function(data) {
                        return `{{ app_setting('site_currency') }} ` + data;
                      },
                    },
                    {
                      data: 'date',
                      name: 'date', 
                    },
                    {
                      data: 'status',
                      name: 'status',
                      searchable: false,
                    },
                ]
            })

            $('#statusFilter').on('change', function() {
                table.ajax.reload(); // Reload the DataTable when the select field changes
            });

        });
 
</script>

@endsection