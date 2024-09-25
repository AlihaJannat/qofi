@extends('layout.adminapp')
@section('content')
    <style>
        img.category {
            width: 100%;
            height: 40vh;
            object-fit: contain;
            cursor: pointer;
        }

        img.category:hover {
            -webkit-filter: brightness(70%);
            -webkit-transition: all 1s ease;
            -moz-transition: all 1s ease;
            -o-transition: all 1s ease;
            -ms-transition: all 1s ease;
            transition: all 1s ease;
        }

        .img_wrap {
            position: relative;
            text-align: left;
        }

        .select2-results__option[aria-selected=true] {
            display: none;
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <a href="{{ route('admin.filter.index') }}" class="text-muted fw-light">Filter /</a> Edit
        </h4>
        <div class="row px-5">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Edit Filter ({{ $filter->name }})</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
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
                            </div>
                        </div>
                        <div class="row">
                            <form action="{{ route('admin.filter.img.change') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="img_wrap">
                                    <img class="category" onclick="changeImg({{ $filter->id }})"
                                        src="{{ filter_var($filter->image, FILTER_VALIDATE_URL) ? $filter->image : asset('images/' . $filter->image) }}" />
                                </div>
                                <input type="hidden" value="{{ $filter->id }}" name="id">
                                <input type="file" id="myFile{{ $filter->id }}"
                                    onchange="onMyFileChange(this, {{ $filter->id }})" name="image"
                                    style="display:none" />
                                <input type="submit" id="submit{{ $filter->id }}" style="display:none">
                            </form>

                            <form action="{{ route('admin.filter.edit', $filter->id) }}" class="row g-3" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" value="{{$filter->name}}" name="name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Sort Order</label>
                                        <input type="number" name="sort_order" value="{{ $filter->sort_order }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function changeImg(id) {
            $('#myFile' + id).click()
        }

        function onMyFileChange(itSelf, id) {
            isValid = isValid = validateFile(itSelf.files[0], 3, true)
            if (!isValid) {

                return false;
            }
            $('#submit' + id).click()
        }

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
    </script>
@endsection
