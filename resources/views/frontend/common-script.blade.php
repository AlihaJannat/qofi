<script>
    function validateFile(image, sizeInMb, imageCheck, isSvg = false) {
        if (imageCheck) {
            if (!image.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'error',
                    title: '',
                    text: "Please select only image files.",
                    confirmButtonColor: '#dc3545',
                })
                return false;
            }
            if (isSvg) {
                if (!image.type.startsWith('image/svg+xml')) {
                    Swal.fire({
                        icon: 'error',
                        title: '',
                        text: "Please select only SVG files.",
                        confirmButtonColor: '#dc3545',
                    })
                    return false;
                }
            }
        }
        if (image.size > sizeInMb * 1024 * 1024) {
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

    var addToWishlist;
    var addToCart;
    var togglePasswordField;
    $(document).ready(function() {
        addToWishlist = function(id, btn) {
            addUrl = "{{ route('wishlist.add') }}";
            removeUrl = "{{ route('wishlist.remove') }}";
            const callUrl = $(btn).hasClass('far') ? addUrl : removeUrl;
            $.ajax({
                accept: 'application/json',
                url: callUrl,
                method: 'POST',
                data: {
                    product_id: id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    if ($(btn).hasClass('far')) {
                        $(btn).removeClass('far')
                        $(btn).removeClass('text-black-2')
                        $(btn).addClass('fa')
                        $(btn).addClass('text-btn-pink')
                    } else {
                        $(btn).addClass('far')
                        $(btn).addClass('text-black-2')
                        $(btn).removeClass('fa')
                        $(btn).removeClass('text-btn-pink')
                    }
                },
                error: function(data) {
                    if (data.status == 401) {
                        Swal.fire({
                            title: "Unable to Add",
                            text: "Please login to add items in your wishlist",
                            icon: "error",
                        })

                        return false;
                    }
                    const message = data.responseJSON?.message
                    Swal.fire({
                        title: "Unable to Process",
                        text: message ? message : "Something went wrong",
                        icon: "error",
                    })
                }
            })
        }

        addToCart = function(e, form) {
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
                    Swal.fire({
                        title: "Product Added To Cart",
                        icon: "success",
                        timer: 1000,
                        showConfirmButton: false,
                    });
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
        togglePasswordField = function(btn) {
            $(btn).toggleClass('bx-show');
            $(btn).toggleClass('bx-hide');

            var input = $(btn).closest('div').find('input');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        }
    });
</script>
