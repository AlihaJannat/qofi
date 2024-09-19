@extends('layout.adminapp')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Site Settings</h5>
                    <div class="card-body">
                        <form method="POST" class="row g-3 px-4" action="{{ route('admin.app.setting') }}">
                            @csrf
                            <div class="form-group">
                                <label for="site_name">Site Name</label>
                                <input type="text" name="site_name" class="form-control"
                                    value="{{ app_setting('site_name', '') }}">
                            </div>
                            <div class="form-group">
                                <label>Site Logo</label>
                                <br>
                                <input type="hidden" name="site_logo" value="{{ app_setting('site_logo', '') }}"
                                    id="site_logo">
                                <img src="{{ app_setting('site_logo', '') }}" alt="{{ app_setting('site_logo', '') }}"
                                    width="80px" height="80px">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="changeImage(this, site_logo)">Change</button>
                            </div>
                            <div class="form-group">
                                <label>Footer Logo</label>
                                <br>
                                <input type="hidden" name="site_footer_logo"
                                    value="{{ app_setting('site_footer_logo', '') }}" id="site_footer_logo">
                                <img src="{{ app_setting('site_footer_logo', '') }}"
                                    alt="{{ app_setting('site_footer_logo', '') }}" width="80px" height="80px">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="changeImage(this, site_footer_logo)">Change</button>
                            </div>
                            <div class="form-group">
                                <label>Favicon</label>
                                <br>
                                <input type="hidden" name="site_favicon" value="{{ app_setting('site_favicon', '') }}"
                                    id="site_favicon">
                                <img src="{{ app_setting('site_favicon', '') }}"
                                    alt="{{ app_setting('site_favicon', '') }}" width="80px" height="80px">
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="changeImage(this, site_favicon)">Change</button>
                            </div>
                            <div class="form-group">
                                <label for="site_currency">Site Currency</label>
                                <input type="text" name="site_currency" class="form-control"
                                    value="{{ app_setting('site_currency', 'USD') }}">
                            </div>
                            <div class="form-group">
                                <label for="fb_url">Facebook URL</label>
                                <input type="text" name="fb_url" class="form-control" value="#">
                            </div>
                            <div class="form-group">
                                <label for="twitter_url">Twitter URL</label>
                                <input type="text" name="twitter_url" class="form-control"
                                    value="{{ app_setting('twitter_url', '#') }}">
                            </div>
                            <div class="form-group">
                                <label for="insta_url">Insta URL</label>
                                <input type="text" name="insta_url" class="form-control"
                                    value="{{ app_setting('insta_url', '#') }}">
                            </div>
                            <div class="form-group">
                                <label for="site_email">Site Email</label>
                                <input type="text" name="site_email" class="form-control"
                                    value="{{ app_setting('site_email', 'info@site.com') }}">
                            </div>
                            <div class="form-group">
                                <label for="whatsapp">Whatsapp</label>
                                <input type="number" name="whatsapp" class="form-control"
                                    value="{{ app_setting('whatsapp', '44445555') }}">
                            </div>
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control"
                                    value="{{ app_setting('latitude', '44445555') }}">
                            </div>
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control"
                                    value="{{ app_setting('longitude', '44445555') }}">
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">
                                    SAVE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- edit item modal --}}
    <div class="modal fade" id="image-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Select an Image</h3>
                    </div>
                    <div class="row modal-img-div">
                        @foreach ($images as $image)
                            <div class="col-md-3 my-2 main-div">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ asset(str_replace('\\', '', $image->url)) }}" alt="{{ $image->name }}"
                                        width="80vw" height="80vh">
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"
                                            onclick="selectedImage('{{ $image->name }}')">Select</button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="deleteImage(this, '{{ $image->name }}')">Delete</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <label for="new-img">Upload image
                            &nbsp;&nbsp;<i class="fa fa-2x fa-upload" aria-hidden="true" style="cursor: pointer"></i>
                            <span id="main-img-info_default" class="text-secondary"></span></label>
                        <input type="file" id="new-img" name="new_img" style="display: none"
                            onchange="imageUpload(this)" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var changeImage
        var selectedImage
        var deleteImage
        var imageUpload
        inputId = null

        function validateImage(image) {
            if (!image.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'error',
                    title: '',
                    text: "Please select only image files.",
                    confirmButtonColor: '#dc3545',
                })
                return false;
            }
            if (image.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File size too large',
                    text: 'Please select an image file smaller than 2MB.',
                    confirmButtonColor: '#dc3545',
                });
                return false;
            }
            return true;
        }


        $(document).ready(function() {
            $('.time-select2').select2()
            changeImage = function(itSelf, key) {
                inputId = key
                $('#image-modal').modal('show');
            }

            selectedImage = function(imageName) {
                imageUrl = "{{ asset('assets/images/admin') }}/" + imageName
                $(inputId).val(imageUrl)
                $(inputId).closest('div').find('img').attr('src', imageUrl)
                $('#image-modal').modal('hide');
            }

            deleteImage = function(itSelf, imageName) {

                Swal.fire({
                    title: 'Are you sure you want to delete this image?',
                    text: 'The seleted action can not be undone',
                    showDenyButton: true,
                    confirmButtonText: 'DELETE',
                    denyButtonText: `CANCEL`,
                    confirmButtonColor: '#dc3545',
                    denyButtonColor: '#444',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.delete.img') }}",
                            method: 'DELETE',
                            data: {
                                'imageName': imageName
                            },
                            success: function(data) {
                                $(itSelf).closest('div.main-div').addClass('d-none')
                            },
                            error: function(data) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '',
                                    text: "Something went wrong",
                                    confirmButtonColor: '#dc3545',
                                })
                                return false;
                            }
                        })
                    }
                })
            }

            imageUpload = function(input) {
                var file = input.files[0];
                isValid = validateImage(file)
                if (!isValid) {
                    input.value = '';
                    $(input).closest('div').find('span').html('')
                    return false;
                }
                formData = new FormData()
                formData.append('image', file)
                formData.append('_token', "{{csrf_token()}}")
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('admin.add.img') }}",
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        htmlImg =
                            '<div class="col-md-3 my-2 main-div"><div class="d-flex flex-column align-items-center"><img src="' +
                            data.imgUrl + '" alt="' + data.imgName +
                            '"width="80vw" height="80vh">' +
                            `<div><button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectedImage('` +
                            data.imgName +
                            `')">Select</button><button type="button" class="btn btn-danger btn-sm" onclick="deleteImage(this, '` +
                            data.imgName + `')">Delete</button></div></div></div>`
                        $('.modal-img-div').append(htmlImg)
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: "Something went wrong",
                            confirmButtonColor: '#dc3545',
                        })
                        return false;
                    }
                })
            }

        });
    </script>
@endsection
