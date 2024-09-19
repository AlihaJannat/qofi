@extends('frontend.user.layout')
@section('title')
    | User's Favourite
@endsection
@section('child-content')
    <div class="w-full h-full md:w-3/4 bg-card-bg">
        @livewire('user.favourite')
    </div>
@endsection
@section('script')
    <script>
        // document.addEventListener('livewire:init', () => {
        //     Livewire.on('flash-message', (event) => {
        //         Swal.fire({
        //             title: "Password change successful",
        //             icon: "success",
        //             timer: 1000,
        //             showConfirmButton: false,
        //         });
        //     });
        // });
        $(document).ready(function() {
            $('#favourite-info-link').addClass('bg-user-nav-pink-active')
        })
    </script>
@endsection
