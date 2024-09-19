@extends('layout.adminapp')
@section('content')
    <style>
        img.shop {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            cursor: pointer;
        }

        img.shop:hover {
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
            <a href="{{ route('admin.shop.index') }}" class="text-muted fw-light">Shops /</a> New
        </h4>
        <div class="row px-5">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">New Shop</h5>
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
                        <form action="{{ route('admin.shop.new') }}" class="row g-3" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', null) }}" placeholder="Enter Name"
                                        required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Owner</label>
                                    <select name="owner_id" value="{{ old('owner_id', null) }}" class="form-select" required>
                                        <option value="">Select Owner</option>
                                        @foreach ($shopOwners as $owner)
                                            <option value="{{ $owner->id }}">
                                                {{ $owner->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State</label>
                                    <select name="state_id" value="{{ old('state_id', null) }}" class="form-select" required onchange="getCities(this.value)">
                                        <option value="">Select State</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">
                                                {{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <select name="city_id" value="{{ old('city_id', null) }}" id="city_id" class="form-select" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="number" value="{{ old('latitude', null) }}" step="0.0000001" name="latitude" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="number" value="{{ old('longitude', null) }}" step="0.0000001" name="longitude" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" value="{{ old('image', null) }}" name="image" class="form-control" required>
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
        function changeImg(id) {
            $('#myFile' + id).click()
        }

        function onMyFileChange(itSelf, id) {
            isValid = validateFile(itSelf.files[0], 3, true, false)
            if (!isValid) {

                return false;
            }
            $('#submit' + id).click()
        }

        var getCities

        $(document).ready(function() {

            getCities = function(state_id) {
                $.ajax({
                    url: "{{ route('admin.location.get.cities') }}",
                    method: "get",
                    data: {
                        state_id,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $("#city_id").empty();
                    },

                    success: function(data) {
                        if (data.cities) {
                            data.cities.map((city) => {
                                $("#city_id").append('<option value="' + city.id + '">' +
                                    city.name +
                                    '</option>');
                            })
                        }
                    }
                });
            }
        });
    </script>
@endsection
