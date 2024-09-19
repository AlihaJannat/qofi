@extends('frontend.user.layout')
@section('title')
    | User's Profile
@endsection

@section('child-content')
<div class="w-full h-full md:w-3/4 bg-card-bg">
    @livewire('user.profile', ['user' => $user], key($user->id))
    <div class="w-full my-3 p-2 bg-[#555555] flex justify-end items-center gap-2">
        <div class="flex flex-col justify-start items-start gap-2">
            <a href="{{route('user.profile.delete')}}"
                class="h-10 px-4 py-2 bg-[#FBEDBC] rounded-md flex justify-start items-center gap-2 hover:no-underline focus:outline-none"
                onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                <div class="w-32 flex justify-start items-center gap-3">
                    <div class="text-light-pink text-sm font-rubik font-medium leading-6">
                        Delete my account
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function toggleUserSidebar() {
            var sidebar = document.getElementById('sidebarUser');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
            }
        }

        function checkImage(input) {
            var file = input.files[0];
            isValid = validateFile(file, 2, true)
            if (isValid) {
                // Get the URL of the selected file and set it as the src attribute of the image tag
                var imagePreview = document.getElementById("profile-image");
                var fileURL = URL.createObjectURL(file);
                imagePreview.src = fileURL;
                $('#profile-label').toggleClass('hidden');
                $('#profile-label').toggleClass('flex');
                $('#profile-btn').toggleClass('hidden');
                $('#profile-btn').toggleClass('flex');
            } else {
                input.value = ''; // Clear the file input if the file is not valid
                // You may also want to display an error message here
            }
        }

        var updateProfileImage;
        $(document).ready(function() {
            $('#profile-info-link').addClass('bg-user-nav-pink-active')
            updateProfileImage = function(e, form) {
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
                        $('#profile-label').toggleClass('hidden');
                        $('#profile-label').toggleClass('flex');
                        $('#profile-btn').toggleClass('hidden');
                        $('#profile-btn').toggleClass('flex');
                    },
                    error: function(data) {
                        var imagePreview = document.getElementById("profile-image");
                        imagePreview.src = "{{asset('images'.$user->image)}}";
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
        })
    </script>
@endsection
