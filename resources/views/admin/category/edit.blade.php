@extends('layout.adminapp')
@section('content')
    <style>
        img.category {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
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
            <a href="{{ route('admin.category.index') }}" class="text-muted fw-light">Category /</a> Edit
        </h4>
        <div class="row px-5">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Edit Category ({{ $category->name }})</h5>
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
                            <div class="img_wrap">
                                <img class="category" onclick="changeImg({{ $category->id }})"
                                    src="{{ filter_var($category->image_name, FILTER_VALIDATE_URL) ? $category->image_name : asset('images' . $category->image_name) }}" />
                            </div>
                        </div>

                        <form action="{{ route('admin.category.edit', ['id' => $category->id]) }}" class="row g-3"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Parent Category</label>
                                    <select name="parent_id" class="form-select" onchange="toggleNavField(this.value)" >
                                        <option value="">--Select Parent Category--</option>
                                        @foreach ($categories as $c)
                                            <option value="{{$c->id}}" {{$category->parent_id == $c->id ? 'selected': ''}}>{{$c->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 nav-div {{$category->parent_id ? 'd-none': ''}}">
                                <div class="form-group">
                                    <label>Show on Navbar?</label>
                                    <select name="show_nav" class="form-select">
                                        <option value="0" {{$category->show_nav ? '': 'selected'}}>No</option>
                                        <option value="1" {{$category->show_nav ? 'selected': ''}}>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" value="{{ $category->name }}" name="name"
                                        placeholder="Enter Name" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="main-image">Image
                                    </label>
                                    <input type="file" id="main-image" name="image" class="form-control"
                                        onchange="onMyFileChange(this)">
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
@endsection
@section('script')
    <script>
        function onMyFileChange(itSelf) {
            isValid = validateFile(itSelf.files[0], 3, true, false)
            if (!isValid) {
                $(itSelf).val(null)
                return false;
            }
        }
        function toggleNavField(category) {
            if (category.length > 0) {
                $('.nav-div').addClass('d-none')
            } else {
                $('.nav-div').removeClass('d-none')
            }
        }
    </script>
@endsection
