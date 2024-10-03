@extends('layout.adminapp')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <a href="{{ route('admin.main-banner.index') }}" class="text-muted fw-light">Main Banner /</a> New
    </h4>
    <div class="row  px-5">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Add New Main Banner</h5>
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

                    <form action="{{ route('admin.main-banner.new') }}" class="row g-3" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="col-6">
                            <div class="form-group">
                                <label>Banner Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="main-image">Image<b class="text-danger">(Required)</b> (1,512 x 540)</label>
                                <input type="file" id="main-image" name="image" class="form-control" required>
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <x-toggle-input label="Has Button" id="has_button" name="has_button" value="0" />

                            </div>
                        </div>


                        <!-- Start of Button Fields -->
                        <div id="buttonFields" class="row" style="display: none;">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="text" name="button_text" class="form-control">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6 pr-1">
                                        <div class="form-group">
                                            <x-color-input name="button_color" id="button_color" label="Button Color" />

                                        </div>
                                    </div>

                                    <div class="col-6 pl-1">
                                        <div class="form-group">

                                            <x-color-input name="button_bg_color" id="button_bg_color"
                                                label="Button Background Color" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- End of Button Fields -->

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
            isValid = validateFile(file, 3, true)
            if (!isValid) {
                input.value = '';
                $(input).closest('div').find('span').html('')
                return false;
            }
            $(input).closest('div').find('span').html(file.name)
        }

        $(document).ready(function() {
        $('#has_button').change(function() {
            if ($(this).is(':checked')) {
                $('#buttonFields').show();
            } else {
                $('#buttonFields').hide();
            }
        });

        if ($('#has_button').is(':checked')) {
            $('#buttonFields').show();
        } else {
            $('#buttonFields').hide();
        }
    });
</script>
@endsection