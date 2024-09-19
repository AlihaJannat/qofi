@extends('layout.vendorapp')
@section('content')
    <style>
        .time-tag {
            padding: 5px;
            border-radius: 7px;
            color: aliceblue;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label">
                    <h5 class="card-title mb-0">Calendar/Delivery Days</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <button data-bs-toggle="modal" class="btn create-new btn-primary mx-3" data-bs-target="#dayModal"
                            type="button"><span><i class="bx bx-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Add Day</span></span></button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="calendarTable">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shopDays as $day)
                            <tr>
                                <td>{{ $day->day?->day }}</td>

                                <td>
                                    @foreach ($day->times as $time)
                                        @php
                                            // Example time
                                            $timeString = $time->time; // e.g., '14:30:00'

                                            // Create a DateTime object
                                            $dateTime = new DateTime($timeString);

                                            // Format the time to 12-hour format with AM/PM
                                            $formattedTime = $dateTime->format('h:i A');

                                            // Add 2 hours to the time
                                            $dateTime->modify('+2 hours');

                                            // Format the new time to 12-hour format with AM/PM
                                            $formattedTimePlusTwoHours = $dateTime->format('h:i A');
                                        @endphp
                                        <span
                                            class="time-tag m-1">{{ $formattedTime . ' - ' . $formattedTimePlusTwoHours }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if ($day->status)
                                        <button class='btn btn-success active'
                                            onclick='statusChange(this,  {{ $day->id }} )'>Active</button>
                                    @else
                                        <button class='btn btn-warning block'
                                            onclick='statusChange(this,  {{ $day->id }} )'>In-Active</button>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $timeIds = $day->times->pluck('id')->toArray();
                                        $timeIdsJson = json_encode($timeIds);
                                    @endphp

                                    <div class='d-inline-block'>
                                        <a href='javascript:;' class='btn btn-sm btn-icon dropdown-toggle hide-arrow'
                                            data-bs-toggle='dropdown'>
                                            <i class='bx bx-dots-vertical-rounded'></i>
                                        </a>
                                        <div class='dropdown-menu dropdown-menu-end m-0'>
                                            <a href="javascript:;" class='dropdown-item'
                                                onclick='editDay("{{$day->day?->day}}", {{$day->id}}, {{ $timeIdsJson }})'>Edit</a>
                                            <div class='dropdown-divider'></div>
                                            <a href='javascript:;' class='dropdown-item text-danger delete-record'
                                                onclick="deleteDay(this,{{ $day->id }})">Delete</a>
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


    <div class="modal fade" id="dayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Add Day</h3>
                    </div>
                    <form class="add-new-owner pt-0" method="POST" action="{{ route('vendor.calendar.day.add') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Day</label>
                            <select name="sw_day_id" class="form-select" required>
                                <option value="">--select day--</option>
                                @foreach ($days as $day)
                                    <option value="{{ $day->id }}">{{ $day->day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time</label>
                            <select name="sw_time_id[]" class="select2-time" multiple required>
                                @foreach ($times as $time)
                                    @php
                                        // Example time
                                        $timeString = $time->time; // e.g., '14:30:00'

                                        // Create a DateTime object
                                        $dateTime = new DateTime($timeString);

                                        // Format the time to 12-hour format with AM/PM
                                        $formattedTime = $dateTime->format('h:i A');

                                        // Add 2 hours to the time
                                        $dateTime->modify('+2 hours');

                                        // Format the new time to 12-hour format with AM/PM
                                        $formattedTimePlusTwoHours = $dateTime->format('h:i A');
                                    @endphp
                                    <option value="{{ $time->id }}">
                                        {{ $formattedTime . ' - ' . $formattedTimePlusTwoHours }}</option>
                                @endforeach
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
    <div class="modal fade" id="editDayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Edit Day (<span id="edit-day"></span>)</h3>
                    </div>
                    <form class="add-new-owner pt-0" method="POST" action="{{ route('vendor.calendar.day.edit') }}">
                        @csrf
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Time</label>
                            <select name="sw_time_id[]" id="edit-time" multiple required>
                                @foreach ($times as $time)
                                    @php
                                        // Example time
                                        $timeString = $time->time; // e.g., '14:30:00'

                                        // Create a DateTime object
                                        $dateTime = new DateTime($timeString);

                                        // Format the time to 12-hour format with AM/PM
                                        $formattedTime = $dateTime->format('h:i A');

                                        // Add 2 hours to the time
                                        $dateTime->modify('+2 hours');

                                        // Format the new time to 12-hour format with AM/PM
                                        $formattedTimePlusTwoHours = $dateTime->format('h:i A');
                                    @endphp
                                    <option value="{{ $time->id }}">
                                        {{ $formattedTime . ' - ' . $formattedTimePlusTwoHours }}</option>
                                @endforeach
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
        function statusChange(itSelf, id) {
            var button = $(itSelf);
            if (button.hasClass('active')) {
                Swal.fire({
                        title: `Are you sure you want to mark this Day as In-Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.calendar.day.status') }}",
                                method: "GET",
                                data: {
                                    id: id,
                                    status: 0
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
                        title: `Are you sure you want to mark this Day as Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.calendar.day.status') }}",
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

        var deleteDay
        var editDay
        $(document).ready(function() {
            var table = $('#calendarTable').DataTable()
            deleteDay = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this Day`,
                        text: `If you delete this it will be gone forever`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.calendar.day.delete') }}",
                                method: "DELETE",
                                data: {
                                    id: id,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    window.location.reload()
                                }
                            })
                        }
                    })
            }
            editDay = function(day, id, timeIds) {
                // Ensure timeIds is an array and contains the expected data
                if (Array.isArray(timeIds)) {
                    // Continue with your edit logic here
                    // Example: log each id
                    timeIds.forEach(id => {
                        console.log('ID:', id);
                    });
                    $('#edit-day').html(day)
                    $('#edit-id').val(id)
                    // Deselect all options in the multi-select
                    $('#edit-time').val([]).trigger('change');

                    // Select the options based on the timeIds array
                    $('#edit-time').val(timeIds).trigger('change');

                    $('#editDayModal').modal('show')
                } else {
                    console.error('Invalid timeIds:', timeIds);
                }
            }
            $('.select2-time').select2({
                closeOnSelect: false,
                dropdownParent: $('#dayModal'),
            })
            $('#edit-time').select2({
                closeOnSelect: false,
                dropdownParent: $('#editDayModal'),
            })
        });
    </script>
@endsection
