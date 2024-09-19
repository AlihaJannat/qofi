@extends('layout.adminapp')
@section('content')
<style>
    img.image:hover {
            -webkit-filter: brightness(70%);
            -webkit-transition: all 1s ease;
            -moz-transition: all 1s ease;
            -o-transition: all 1s ease;
            -ms-transition: all 1s ease;
            transition: all 1s ease;
        }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        Attribute
    </h4>
    <input type="hidden" name="product_attribute_set_id" id="product_attribute_set_id"
        value="{{$product_attribute_set_id}}">
    <div class="card">
        <div class="card-header flex-column flex-md-row">
            <div class="head-label">
                <h5 class="card-title mb-0">Attribute</h5>
            </div>
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons btn-group flex-wrap">
                    <button data-bs-toggle="modal" class="btn create-new btn-primary" data-bs-target="#attributeModal"
                        type="button"><span><i class="bx bx-plus me-sm-1"></i>
                            <span class="d-none d-sm-inline-block">Add New
                                Attribute</span></span></button>
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-row-grouping table table-bordered" id="attributeTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Icon</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- attribute modal --}}
<div class="modal fade" id="attributeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Add Set</h3>
                </div>
                <form class="add-new-attribute pt-0" onsubmit="addAttribute(event, this)"
                    action="{{ route('admin.productattributeset.attribute.add') }}">
                    @csrf
                    <input type="hidden" name="sw_product_attribute_set_id" value="{{$product_attribute_set_id}}">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Title" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" placeholder="Image" />
                    </div>
                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
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
                    <h3>Edit Attribute</h3>
                </div>
                <form action="{{ route('admin.productattributeset.attribute.imgupdate') }}" onsubmit="updateAttributeImage(event, this)" >
                    @csrf
                    <div class="img_wrap text-center">
                        <img class="image p-2 border border-2 border-black hover:shadow-lg transition-shadow duration-300 ease-in-out" onclick="changeImg()"
                            src="" id="edit-image-tag"  width="80" />
                    </div>
                    <input type="hidden" name="id" id="edit-id-image" >
                    <input type="file" id="myFile"
                        onchange="onMyFileChange(event,this)" name="image"
                        style="display:none" />
                    <input type="submit" id="submitimage" style="display:none">
                </form>
                <form class="pt-0" onsubmit="updateAttribute(event, this)"
                    action="{{ route('admin.productattributeset.attribute.update') }}">
                    @csrf
                    <input type="hidden" name="sw_product_attribute_set_id" value="{{$product_attribute_set_id}}">

                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="edit-title" class="form-control" name="title" placeholder="Title" />
                    </div>
                    <button class="btn btn-primary me-sm-3 me-1 data-submit">Update</button>
                    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
        var productAttributeSetId = $('#product_attribute_set_id').val();
        var addAttribute
        var deleteAttribute
        var editAttribute
        var updateAttribute
        var updateAttributeImage
        $(document).ready(function() {

            var ajaxUrl = `{{ url('admin/productattributeset/attribute/yajra') }}/${productAttributeSetId}`;
            var table = $('#attributeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: ajaxUrl,
                columns: [
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        seachable: false,
                        sortable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        seachable: false,
                        sortable: false
                    },
                ]
            })

            editAttribute = function(attribute, id) {
                console.log(attribute);
                $('#edit-id').val(attribute.id);
                $('#edit-id-image').val(attribute.id);
                $('#edit-title').val(attribute.title);
                const image = "{{ asset('images') }}"+attribute.image;
                $('#edit-image-tag').attr('src', image);

                $('#editAttributeModal').modal('show');
            }

            updateAttribute = function(e, itSelf) {
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
            updateAttributeImage = function(e, itSelf) {
                console.log('sending');
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

            deleteAttribute = function(itSelf, id) {

                var RowToRemove = $(itSelf).parents('tr');
                Swal.fire({
                        title: `Are you sure you want to delete this record?`,
                        text: "If you delete this record, it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.productattributeset.attribute.delete') }}",
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success: function(data) {
                                    RowToRemove
                                        .remove()
                                }
                            })
                        }
                    })
            }


            $('#perform-delete').click(function() {
                const checkedCheckboxes = $('.bulk-checkbox:checked');

                if (checkedCheckboxes.length === 0) {
                    Swal.fire({
                        title: `No Checkbox Selected`,
                        icon: "warning",
                    })
                    return;
                }

                let attributeIds = []

                Swal.fire({
                        title: `Are you sure you want to delete these records?`,
                        text: "If you delete it will be gone forever with all its instences.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                    })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed) {
                            checkedCheckboxes.each(function() {
                                attributeIds.push($(this).val());
                            });
                            $.ajax({
                                url: "{{ route('admin.productattributeset.attribute.delete') }}",
                                method: "DELETE",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    action: 'bulk',
                                    attributeIds
                                },
                                beforeSend: function() {
                                    Swal.fire({
                                        title: 'Loading...',
                                        text: 'Please wait',
                                        icon: 'info',
                                        buttons: false,
                                        closeOnClickOutside: false,
                                        closeOnEsc: false,
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        showCloseButton: false,
                                    });
                                },
                                success: function(data) {
                                    table.draw(false);
                                    Swal.close()
                                },
                                error: function(data) {
                                    table.draw(false);
                                    Swal.close()
                                }
                            })

                        }
                    })
                // Perform your action on the selected checkboxes

            });
        });
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function changeImg() {
            
            $('#myFile').click()
        }

        function onMyFileChange(e,itSelf) {
            
            isValid = isValid = validateFile(itSelf.files[0], 3, true)
            if (!isValid) {

                return false;
            }
            
            $('#submitimage').click();
            updateAttributeImage(e,itSelf);
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