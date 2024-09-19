<script>
    var togglePasswordField;
    $(document).ready(function() {
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
    })
</script>
