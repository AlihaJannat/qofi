@extends('layout.vendorapp')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label">
                    <h5 class="card-title mb-0">Calendar Times</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="calendarTable">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($times as $time)
                            <tr>
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
                                <td>{{ $formattedTime }} - {{ $formattedTimePlusTwoHours }}</td>
                                <td>
                                    @if ($time->status)
                                        <button class='btn btn-success active'
                                            onclick='statusChange(this,  {{ $time->id }} )'>Active</button>
                                    @else
                                        <button class='btn btn-warning block'
                                            onclick='statusChange(this,  {{ $time->id }} )'>In-Active</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
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
                        title: `Are you sure you want to mark this Time as In-Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.calendar.time.status') }}",
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
                        title: `Are you sure you want to mark this Time as Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.calendar.time.status') }}",
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
            var table = $('#calendarTable').DataTable({
                "order": []
            });
        });
    </script>
@endsection
