@extends('layout.adminapp')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <a href="{{ route('admin.category.index') }}" class="text-muted fw-light">Category /</a> New
        </h4>
        <div class="row  px-5">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Add New Category</h5>
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

                        <form action="{{ route('admin.category.new') }}" class="row g-3" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Parent Category</label>
                                    <select name="parent_id" onchange="toggleNavField(this.value)" class="form-select">
                                        <option value="">--Select Parent Category--</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 nav-div">
                                <div class="form-group">
                                    <label>Show on Navbar?</label>
                                    <select name="show_nav" class="form-select">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="main-image">Main<b class="text-danger">(Required)</b>
                                    </label>
                                    <input type="file" id="main-image" name="image" class="form-control"
                                        onchange="mainImageCheck(this)" required>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mainImageCheck(input) {
            var file = input.files[0];
            isValid = validateFile(file, 3, true, false)
            if (!isValid) {
                input.value = '';
                $(input).closest('div').find('span').html('')
                return false;
            }
            $(input).closest('div').find('span').html(file.name)
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
