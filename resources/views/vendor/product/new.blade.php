@extends('layout.vendorapp')
@section('content')
<style>
    .select2-selection__choice,
    .select2-results__option {
        display: flex;
        align-items: center
    }

    #add_variation_main_block {
        display: none;
        /* Initially hide the block */
        opacity: 0;
        transition: opacity 0.5s ease;
        /* Smooth transition */
    }

    #add_variation_main_block.show  {
        display: block;
        opacity: 1;
    }

    .remove-variation-btn {
        background-color: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        text-align: center;
        cursor: pointer;
        float: right;
    }
</style>

{{-- @php
$vendor = auth('vendor')->user();
$isOwner = $vendor->isOwner();
@endphp --}}

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <a href="{{ route('vendor.product.index') }}" class="text-muted fw-light">Products /</a> New
    </h4>
    <div class="row px-5">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">New Product</h5>
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
                    <form action="{{ route('vendor.product.create') }}" class="card-body" method="post"
                        enctype="multipart/form-data" id="product-form">
                        @csrf
                        <h6 class="fw-normal">1. Basic Info</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Name"
                                        required>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Categories</label>
                                    <select name="sw_category_id" onchange="getChildren(this.value)" class="form-select"
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
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
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Colors</label>
                                    <select name="color_id[]" class="form-select color-select" multiple>
                                        @foreach ($colors as $color)
                                        <option value="{{ $color->id }}" data-color="{{ $color->hex_code }}">
                                            {{ $color->name }}
                                        </option>
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
                                        <option value="{{ $country->id }}">
                                            {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>In-Stock?</label>
                                    <select name="in_stock" class="form-select" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" name="price" id="price" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="text" name="stock" id="stock" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Discount(0 if no discount)</label>
                                <input type="number" step="0.01" class="form-control" name="discount"
                                    placeholder="10"  />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-select">
                                    <option value="percent">Percent %</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea name="short_description" class="form-control" required
                                        rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Long Description</label>
                                    <textarea name="long_description" class="form-control" required rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-bold">
                                        <input type="checkbox" name="has_variation" id="has_variation">
                                        Has Variations</label>
                                        <p class="text-danger">You can add Variation once you add the product.</p>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="add_variation_block " id="add_variation_main_block">
                            <div class="add_variation_block " id="add_variation_block">
                                <hr class="my-4 mx-n4">
                                <button type="button" class="remove-variation-btn"
                                    onclick="removeVariation(this)">x</button>
                                <h6 class="fw-normal">Variations</h6>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attribute</label>
                                            <select name="sw_category_id" onchange="getChildrenAttribute(this.value)"
                                                class="form-select" required>
                                                <option value="">Select Attribute</option>
                                                @foreach ($product_attribute_set as $pas)
                                                <option value="{{ $pas->id }}">
                                                    {{ $pas->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attribute</label>
                                            <select name="attribute_id" id="child-product-attribute-set"
                                                class="form-select">
                                                <option value="">Select Attribute</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" name="price" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Initial Stock</label>
                                        <input type="number" step="1" class="form-control" name="stock" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Discount(0 if no discount)</label>
                                        <input type="number" step="0.01" class="form-control" name="discount"
                                            placeholder="10" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Discount Type</label>
                                        <select name="discount_type" class="form-select">
                                            <option value="percent">Percent %</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Button to add more variations -->
                            <button type="button" id="add-variation-btn" class="btn btn-info my-2">Add More
                                Variation</button>
                        </div> --}}


                        {{--
                        <hr class="my-4 mx-n4">
                        <h6 class="fw-normal">Height / Price</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Height (in cm)</label>
                                <input type="number" step="0.01" class="form-control" name="height" placeholder="20.2"
                                    required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Unit</label>
                                <select name="sw_unit_id" class="form-select">
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="price" required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount(0 if no discount)</label>
                                <input type="number" step="0.01" class="form-control" name="discount" placeholder="10"
                                    required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-select">
                                    <option value="percent">Percent %</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Initial Stock</label>
                                <input type="number" step="1" class="form-control" name="stock" required />
                            </div>
                        </div> --}}
                        <hr class="my-4 mx-n4">
                        <h6 class="fw-normal">Images</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
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
                            </div>
                        </div>
                        {{--
                        <hr class="my-4 mx-n4">
                        <h6 class="fw-normal">3. Default Height</h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Height Value</label>
                                <input type="number" step="0.01" class="form-control" name="value" placeholder="20.2"
                                    required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Unit</label>
                                <select name="sw_unit_id" class="form-select">
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="price" required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount(0 if no discount)</label>
                                <input type="number" step="0.01" class="form-control" name="discount" placeholder="10"
                                    required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-select">
                                    <option value="percent">Percent %</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Initial Stock</label>
                                <input type="number" step="1" class="form-control" name="stock" required />
                            </div>
                        </div> --}}
                        {{-- @if ($isOwner)
                        <div class="row mt-3">
                            <div class="col-12">
                                <input class="form-check-input" name="all_shops" type="checkbox" id="all-shop">
                                <label class="form-check-label" for="all-shop">
                                    Create for All Shops?
                                </label>
                            </div>
                        </div>
                        @endif --}}

                        
                        <div class="col-12 pt-4">
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


        var getChildren;
        var getChildrenAttribute;
        $(document).ready(function() {
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


            getChildren = function(id) {
                $.ajax({
                    url: "{{ route('vendor.get.children') }}",
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

            getChildrenAttribute = function(id) {
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
                    },
                    success: function(data) {
                        $('#child-product-attribute-set').append($('<option>', {
                            value: '',
                            text: 'Select Variation'
                        }));

                        $.each(data.attributes, function(i, item) {
                            $('#child-product-attribute-set').append($('<option>', {
                                value: item.id,
                                text: item.title
                            }));
                        });
                    },
                    error: function() {
                        alert('Can not fetch category')
                    }
                })
            }


        })


        var removeVariation;
        $(document).ready(function() {
            const hasVariationCheckbox = document.querySelector('#has_variation');
            const addVariationMainBlock = document.querySelector('#add_variation_main_block');
            const textDanger = document.querySelector('.text-danger');

            // Function to toggle visibility with smooth transition
            function toggleVariationBlock() {
                if (hasVariationCheckbox.checked) {
                    textDanger.classList.add('show');
                } else {
                    textDanger.classList.remove('show');
                }
                // if (hasVariationCheckbox.checked) {
                //     addVariationMainBlock.classList.add('show');
                // } else {
                //     addVariationMainBlock.classList.remove('show');
                // }
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


            const addVariationBtn = document.getElementById('add-variation-btn');
            const productForm = document.getElementById('product-form');
            const addVariationBlock = document.querySelector('#add_variation_block');

            addVariationBtn.addEventListener('click', function() {
                const newVariationBlock = addVariationBlock.cloneNode(true);

                // Reset the values of the cloned block's inputs
                // const inputs = newVariationBlock.querySelectorAll('input, select');
                // inputs.forEach(input => {
                //     if (input.type !== 'hidden') {
                //         input.value = '';
                //     }
                // });

                addVariationBlock.appendChild(newVariationBlock);
            });

            removeVariation = function(button) {
                const variationBlock = button.parentElement;
                variationBlock.remove();
            }

        });
</script>
@endsection