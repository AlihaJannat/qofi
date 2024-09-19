@extends('frontend.user.layout')
@section('title')
    | User's Password
@endsection
@section('child-content')
    <div class="w-full h-full md:w-3/4 bg-card-bg">
        @livewire('user.password')
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('flash-message', (event) => {
                Swal.fire({
                    title: "Password change successful",
                    icon: "success",
                    timer: 1000,
                    showConfirmButton: false,
                });
            });
        });
        $(document).ready(function() {
            $('#profile-password-link').addClass('bg-user-nav-pink-active')
        })
    </script>
@endsection
