@extends('layout.adminapp')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label">
                    <h5 class="card-title mb-0">Calendar Days</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row-grouping table table-bordered" id="calendarTable">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($days as $day)
                            <tr>
                                <td>{{$day->day}}</td>
                                <td>
                                    @if ($day->status)
                                    <button class='btn btn-success active'
                                            onclick='statusChange(this,  {{$day->id}} )'>Active</button>
                                    @else
                                    <button class='btn btn-warning block'
                                            onclick='statusChange(this,  {{$day->id}} )'>In-Active</button>
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
                        title: `Are you sure you want to mark this Day as In-Active?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.calendar.day.status') }}",
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
                                url: "{{ route('admin.calendar.day.status') }}",
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
            var table = $('#calendarTable').DataTable()
        });
    </script>
@endsection
