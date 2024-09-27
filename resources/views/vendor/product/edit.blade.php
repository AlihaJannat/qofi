@extends('layout.vendorapp')
@section('content')
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 14px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #28a745;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    .add-attribute {
        display: flex;
        justify-content: end;
        align-items: center;
    }

    .add-attribute-2 {
        display: flex;
        align-items: center;
    }

    .main-attribute-add {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    img.image_product_main {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
        cursor: pointer;
    }

    img.image_product {
        border-radius: 50%;
        width: 120px;
        height: 120px;
        object-fit: cover;
        cursor: pointer;
    }

    img.image_product:hover,
    img.image_product_main:hover {
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

    .select2-selection__choice,
    .select2-results__option {
        display: flex;
        align-items: center
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <a href="{{ route('vendor.product.index') }}" class="text-muted fw-light">Products /</a> Edit
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Edit Product</h5>
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
                        <div class="col-12">
                            <h4>Main Image</h4>
                        </div>
                        <form action="{{ route('vendor.product.img.change') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="img_wrap">
                                <img src="{{ asset('/images' . $product->image_name) }}" class="mb-3"
                                    style="width: 30%; height: 30%;" alt="">
                            </div>
                            <button type="button" class="btn btn-info"
                                onclick="changeImg('{{ $product->id }}main')">Change Image</button>
                            <input type="hidden" name="main" value="true">
                            <input type="hidden" value="{{ $product->id }}" name="product_img_id">
                            <input type="file" id="myFile{{ $product->id }}main"
                                onchange="onMyFileChangeMain('{{ $product->id }}main', this)" name="image"
                                style="display:none" />
                            <input type="submit" id="submit{{ $product->id }}main" style="display:none">
                        </form>
                    </div>
                    <div class="my-5">
                        <div class="row">
                            <div class="col-12">
                                <h5>Optional Images</h5>
                            </div>
                            <div class="d-flex">
                                {{-- showing optional images with form to update those --}}
                                @foreach ($product->images as $image)
                                <div id="row" class="d-flex flex-column mx-1">
                                    <form action="{{ route('vendor.product.img.change') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="img_wrap mr-5">
                                            <img class="image_product" onclick="changeImg({{ $image->id }})"
                                                src="{{ asset('/images' . $image->image_name) }}" />
                                        </div>
                                        <input type="hidden" value="{{ $image->id }}" name="product_img_id">
                                        <input type="file" id="myFile{{ $image->id }}"
                                            onchange="onMyFileChange({{ $image->id }})" name="image"
                                            style="display:none" />
                                        <input type="submit" id="submit{{ $image->id }}" style="display:none">
                                    </form>
                                    <button class="btn btn-danger ml-4 mt-2" id="DeleteRow"
                                        onclick="onTrashIconClick(this, {{ $image->id }})" type="button"><i
                                            class="fa fa-trash"></i></button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- to add more optional images --}}
                        <form action="{{ route('vendor.product.img.new') }}" id="new-img-form" method="post"
                            enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @csrf
                            <input type="file" name="image" id="product-new-img" style="display: none;">
                        </form>
                        <button class="btn btn-primary mt-4" id="add-more-img">Add Image</button>
                    </div>

                    <form action="{{ route('vendor.product.edit', [$product->id]) }}" class="row g-3" method="post">
                        @csrf
                        <div class="col-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{ $product->name }}" name="name"
                                    placeholder="Enter Name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Categories</label>
                                <select name="sw_category_id" onchange="getChildren(this.value)" class="form-select"
                                    required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->sw_category_id == $category->id ?
                                        'selected' : '' }}>
                                        {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sub Categories</label>
                                <select name="child_category_id" id="child-categories" class="form-select">
                                    <option value="">Select Sub Category</option>
                                    @foreach ($childCategories as $child)
                                    <option value="{{ $child->id }}" {{ $product->child_category_id == $child->id ?
                                        'selected' : '' }}>
                                        {{ $child->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Colors</label>
                                <select name="color_id[]" class="form-select color-select" multiple>
                                    @foreach ($colors as $color)
                                    <option value="{{ $color->id }}" data-color="{{ $color->hex_code }}" {{
                                        in_array($color->id, $selectedColors) ? 'selected' : '' }}>
                                        {{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country</label>
                                <select name="country_id" class="form-select" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ $product->country_id == $country->id ?
                                        'selected' : '' }}>
                                        {{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>In-Stock?</label>
                                <select name="in_stock" class="form-select" required>
                                    <option value="1" {{ $product->in_stock ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $product->in_stock ? '' : 'selected' }}>No</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <label class="form-label">Height (in cm)</label>
                            <input type="number" step="0.01" value="{{$product->height}}" class="form-control"
                                name="height" placeholder="20.2" required />
                        </div> --}}
                        {{-- <div class="col-md-4">
                            <label class="form-label">Unit</label>
                            <select name="sw_unit_id" class="form-select">
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" value="{{$product->price}}" class="form-control"
                                name="price" required />
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stock</label>
                                <input type="text" name="stock" id="stock" value="{{$product->stock}}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select">
                                <option value="percent">Percent %</option>
                                <option value="fixed" {{$product->discount_type == 'percent' ? '' : 'selected'}} >Fixed
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Discount(0 if no discount)</label>
                            <input type="number" step="0.01" value="{{$product->discount}}" class="form-control"
                                name="discount" placeholder="10" />
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Short Description</label>
                                <textarea name="short_description" class="form-control" required
                                    rows="5">{{ $product->short_description }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Long Description</label>
                                <textarea name="long_description" class="form-control" required
                                    rows="5">{{ $product->long_description }}</textarea>
                            </div>
                        </div>
                        {{--
                        <hr class="my-4 mx-n4">
                        <h6 class="fw-normal">Images</h6> --}}
                        <div class="row g-3">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" name="image_name" required onchange="mainImageCheck(this)"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image">Upload images can select multiple &nbsp;&nbsp;<i
                                            class="fa fa-2x fa-upload" aria-hidden="true" style="cursor: pointer"></i>
                                        <span id="upload-file-info" class="text-secondary"></span></label>
                                    <input type="file" id="image" multiple name="image[]" class="form-control d-none"
                                        onchange="updateList(this);">
                                </div>
                                <p>Selected files:</p>

                                <div id="fileList"></div>
                            </div> --}}

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-bold">

                                        <x-toggle-input id="is_featured" name="is_featured" label="Is Featured"
                                            value="{{$product->is_featured}}" />

                                    </label>
                                </div>
                            </div>


                            <div class="">
                                <div class="col-12 main-attribute-add">
                                    <div class="form-group ">
                                        <input type="checkbox" name="has_variation" id="has_variation" class="mr-2"
                                            {{$product->has_variation ? 'checked' : ''}}>
                                        <label for="has_variation" class="font-bold">Has Variations</label>
                                    </div>

                                    <div class="add-attribute" id="add_variation_btn">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#addAttributeModal">Add
                                            Attribute</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- attributes table --}}
                        <div class="border border-blue-50" id="variation_table">
                            <div class="card-datatable table-responsive">
                                <table class="dt-row-grouping table table-bordered" id="attributeTable">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>


                        <hr class="my-4 mx-n4">
                        {{-- Topping Section --}}
                        @include('vendor.product.partials.toppings')

                        <div class="col-12">
                            <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- product attribute modal --}}
    <div class="modal fade" id="addAttributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <form class="add-new-product-attribute pt-0" onsubmit="addAttribute(event, this)"
                        action="{{ route('vendor.product.attribute.add') }}">
                        @csrf
                        <input type="hidden" name="parent_product_id" value="{{ $product->id }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Attribute</label>
                                <select name="product_variation_set_id" onchange="getChildrenAttribute(this.value)"
                                    class="form-select" required>
                                    <option value="">Select Attribute</option>
                                    @foreach ($product_attribute_set as $pas)
                                    <option value="{{ $pas->id }}">
                                        {{ $pas->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Attribute</label>
                                <select name="variation_id" id="child-product-attribute-set" class="form-select">
                                    <option value="">Select Attribute</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" name="price" required />
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label>In-Stock?</label>
                                <select name="in_stock" class="form-select" required>
                                    <option value="1" {{ $product->in_stock ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $product->in_stock ? '' : 'selected' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" step="1" class="form-control" name="stock" required />
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Discount(0 if no discount)</label>
                            <input type="number" step="0.01" class="form-control" name="discount" placeholder="10" />
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select">
                                <option value="percent">Percent %</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image_name" required onchange="mainImageCheck(this)"
                                    class="form-control">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group ">
                                <label class="switch">
                                    <input type="checkbox" name="is_default" id="is-default">
                                    <span class="slider"></span>
                                </label>
                                <label for="is_default" class="font-bold"> Is Default Variation?</label>
                            </div>

                        </div>

                        <button class="btn btn-primary my-2 me-sm-3 me-1 data-submit">Add</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset my-2" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editAttributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3>Edit Product Attribute Set</h3>
                    </div>


                    <form action="{{ route('vendor.product.attribute.imgupdate') }}"
                        onsubmit="updateVariationImage(event, this)" method="post">
                        @csrf
                        <div class="img_wrap text-center">
                            <img class="image p-2 border border-2 border-black hover:shadow-lg transition-shadow duration-300 ease-in-out"
                                onclick="changeImgVariation()" src="" id="edit-image-tag" width="80" />
                        </div>
                        <input type="hidden" name="id" id="edit-id-image">
                        <input type="hidden" name="product_id" id="edit-product-id">
                        <input type="file" id="myFileVariation" onchange="onMyFileChangeVariation(event,this)"
                            name="image" style="display:none" />
                        <input type="submit" id="submitimage" style="display:none">
                    </form>

                    <form class="pt-0" onsubmit="updateVariation(event, this)"
                        action="{{ route('vendor.product.attribute.update') }}">

                        @csrf
                        <input type="hidden" name="parent_product_id" value="{{ $product->id }}">
                        <input type="hidden" name="id" id="edit-id">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Attribute</label>
                                <select name="variation_id" id="edit-child-product-attribute-set" class="form-select">
                                    <option value="">Select Attribute</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" id="edit-price" class="form-control" name="price"
                                required />
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label>In-Stock?</label>
                                <select name="in_stock" id="edit-in-stock" class="form-select" required>
                                    <option value="1" {{ $product->in_stock ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $product->in_stock ? '' : 'selected' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" step="1" class="form-control" name="stock" id="edit-stock" required />
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Discount(0 if no discount)</label>
                            <input type="number" step="0.01" class="form-control" name="discount" id="edit-discount"
                                placeholder="10" />
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" id="edit-discount-type" class="form-select">
                                <option value="percent">Percent %</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>

                        <button class="btn btn-primary my-2 me-sm-3 me-1 data-submit">Update</button>
                        <button type="reset" class="btn btn-label-secondary btn-reset my-2" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('script')
<script>
    function updateList(input) {
                var files = input.files;
                var imagesList = document.getElementById('fileList');

                imagesList.innerHTML = ''; // clear the list

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];

                    isValid = isValid = validateFile(file, 2, true)
                    if (!isValid) {
                        input.value = '';
                        return false;
                    }

                    // add file name to list
                    var listItem = document.createElement('li');
                    listItem.textContent = file.name;
                    imagesList.appendChild(listItem);
                }

                return true;
        }

        function updateListModal(input) {
                var files = input.files;
                var imagesListModal = document.getElementById('fileListModal');

                imagesListModal.innerHTML = ''; // clear the list

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];

                    isValid = isValid = validateFile(file, 2, true)
                    if (!isValid) {
                        input.value = '';
                        return false;
                    }

                    // add file name to list
                    var listItem = document.createElement('li');
                    listItem.textContent = file.name;
                    imagesListModal.appendChild(listItem);
                }

            return true;
        }
    
        function changeImg(id) {
            
            $('#myFile'+id).click()
        }

        function onMyFileChange(id) {
            $('#submit' + id).click()
        }
        
        function onMyFileChangeMain(id, input) {
            var file = input.files[0];
            isValid = isValid = validateFile(file, 2, true, false)
            if (!isValid) {
                return false;
            }
            $('#submit' + id).click()
        }

        function onTrashIconClick(itSelf, id) {
            Swal.fire({
                    title: `Are you sure you want to delete this Image?`,
                    text: "If you delete this Owner, it will be gone forever with all its instences.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('vendor.product.img.delete') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(data) {
                                $(itSelf).parents("#row").remove();
                            }
                        })
                    }
                })
        }

        var getChildren;
        var getChildrenAttribute;
        var addAttribute
        var deleteAttribute
        var editAttribute
        var updateDefault
        $(document).ready(function() {

            $('#add-more-img').click(function() {
                $('#product-new-img').click()
            })

            $('#product-new-img').change(function() {
                $('#new-img-form').submit();
            })

            function formatColorOption(option) {
                if (!option.id) {
                    return option.text;
                }

                var colorHex = $(option.element).data('color');
                var $option = $(
                    '<span class="mx-2" style="background: ' + colorHex +
                    '; width: 25px; height: 25px; border-radius: 50%; display: inline-block;"></span>' +
                    '<span>' + option.text + '</span>'
                );

                return $option;
            }

            function formatColorSelection(option) {
                if (!option.id) {
                    return option.text;
                }

                var colorHex = $(option.element).data('color');
                var $selection = $(
                    '<span class="mx-2" style="background: ' + colorHex +
                    '; width: 25px; height: 25px; border-radius: 50%; display: inline-block;"></span>' +
                    '<span>' + option.text + '</span>'
                );

                return $selection;
            }

            $('.color-select').select2({
                placeholder: "Select Colors",
                templateResult: formatColorOption,
                templateSelection: formatColorSelection
            })

            
            getChildrenAttribute = function(id) {

                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: "{{ route('vendor.product.get.attribute') }}",
                        method: 'POST',
                        data: {
                            id,
                            _token: "{{csrf_token()}}",
                        },
                        beforeSend: function() {
                            $('#child-product-attribute-set').val(null)
                            $('#child-product-attribute-set').empty()
                            $('#edit-child-product-attribute-set').val(null)
                            $('#edit-child-product-attribute-set').empty()
                        },
                        success: function(data) {
                            $('#child-product-attribute-set').append($('<option>', {
                                value: '',
                                text: 'Select Variation'
                            }));
                            $('#edit-child-product-attribute-set').append($('<option>', {
                                value: '',
                                text: 'Select Variation'
                            }));

                            $.each(data.attributes, function(i, item) {
                                $('#child-product-attribute-set').append($('<option>', {
                                    value: item.id,
                                    text: item.title
                                }));
                                $('#edit-child-product-attribute-set').append($('<option>', {
                                    value: item.id,
                                    text: item.title
                                }));
                            });

                            resolve();
                        },
                        error: function() {
                            alert('Can not fetch category');
                            reject(error);
                        }
                    });
                });
            }

            getChildren = function(id) {
                $.ajax({
                    url: "{{ route('admin.category.get.children') }}",
                    method: 'POST',
                    data: {
                        id,
                        _token: "{{csrf_token()}}",
                    },
                    beforeSend: function() {
                        $('#child-categories').val(null)
                        $('#child-categories').empty()
                    },
                    success: function(data) {
                        $('#child-categories').append($('<option>', {
                            value: '',
                            text: 'Select Sub Category'
                        }));

                        $.each(data.categories, function(i, item) {
                            $('#child-categories').append($('<option>', {
                                value: item.id,
                                text: item.name
                            }));
                        });
                    },
                    error: function() {
                        alert('Can not fetch category')
                    }
                })
            }


        });



        
        var deleteAttribute
        var updateVariation
        var table
        $(document).ready(function() {
            const hasVariationCheckbox = document.querySelector('#has_variation');
            const addVariationBtn = document.querySelector('#add_variation_btn');
            const variationTable = document.querySelector('#variation_table');

           
        
            var table = $('#attributeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vendor.product.attribute.yajra') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.parentProduct = {{$product->id}};
                    }
                },
                columns: [{
                        data: 'image',
                        name: 'image'
                    },{
                        data: 'product.price',
                        name: 'price'
                    },{
                        data: 'product.stock',
                        name: 'stock'
                    },
                    {
                        data: 'edit',
                        name: 'edit'
                    },
                    {
                        data: 'delete',
                        name: 'delete',
                    },
                ]
            })
       
            //
            addAttribute = function(e, itSelf) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(itSelf).attr('action'),
                    method: 'POST',
                    data: new FormData(itSelf),
                    success: function(data) {
                        table.draw(false)
                        $('.btn-reset').click()
                    },
                    error: function(data) {
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
            //

            $('#statusFilter').on('change', function() {
                table.ajax.reload(); // Reload the DataTable when the select field changes
            });

            
            editAttribute = function(variation, id) {
                var atts = getChildrenAttribute(variation.product_variation_set_id);

                $('#edit-id').val(id);
                $('#edit-id-image').val(id);
                $('#edit-product-id').val(variation.product_id);
                $('#edit-price').val(variation.product.price);
                $('#edit-stock').val(variation.product.stock);
                $('#edit-in-stock').val(variation.product.in_stock).change();
                $('#edit-discount').val(variation.product.discount);
                $('#edit-discount-type').val(variation.product.discount_type).change();
                $('#edit-is-default').prop('checked', variation.is_default);
                const image = "{{ asset('images') }}"+variation.product.image_name;
                $('#edit-image-tag').attr('src', image);
                // Ensure the dropdown is populated before setting its value
                atts.then(function() {
                    $('#edit-child-product-attribute-set').val(variation.variation_id).change();
                });
                
                
                $('#editAttributeModal').modal('show');
            }

            updateVariation = function(e, itSelf) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(itSelf).attr('action'),
                    method: 'POST',
                    data: new FormData(itSelf),
                    success: function(data) {
                        table.draw(false)
                        $('.btn-reset').click()
                    },
                    error: function(data) {
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
                });
            }

            updateVariationImage = function(e, itSelf) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(itSelf).attr('action'),
                    method: 'POST',
                    data: new FormData(itSelf),
                    success: function(data) {
                        console.log('done');
                        table.draw(false)
                        $('.btn-reset').click()
                    },
                    error: function(data) {
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

            updateDefault = function(itself,id){
                 $.ajax({
                    url: "{{ route('vendor.product.attribute.updateDefault','') }}/"+id,
                    method: "GET",
                    success: function(data) {
                        table.draw(false);
                    }
                })
            }

            deleteAttribute = function(itSelf, id) {
                var RowToRemove = table.row($(itSelf).parents('tr'));
                Swal.fire({
                        title: `Are you sure you want to delete this Variation?`,
                        text: "",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('vendor.product.attribute.delete') }}",
                                method: "GET",
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    table.draw(false);
                                }
                            })
                        }
                    })
            }

             // Function to toggle visibility with smooth transition
             function toggleVariationBlock() {
                if (hasVariationCheckbox.checked) {
                    addVariationBtn.style.display = "block";
                    variationTable.style.display = "block";
                    
                } else {
                    addVariationBtn.style.display = "none";
                    variationTable.style.display = "none";
                }
            }

            // Add event listener to checkbox
            hasVariationCheckbox.addEventListener('change', toggleVariationBlock);

            // Handle form reset (if applicable)
            document.addEventListener('reset', function(event) {
                if (event.target.contains(hasVariationCheckbox)) {
                    setTimeout(toggleVariationBlock, 0); // Delay to allow form reset
                }
            });

            // Initial check on page load
            toggleVariationBlock();
        });

        //edit variation image
        function changeImgVariation() {
            console.log('clicked');
            $('#myFileVariation').click()
        }

        function onMyFileChangeVariation(e,itSelf) {
            var isValid = validateFile(itSelf.files[0], 3, true)
            if (!isValid) {
                return false;
            }
            
            $('#submitimage').click();
            updateVariationImage(e,itSelf);
        }

        
</script>
@endsection