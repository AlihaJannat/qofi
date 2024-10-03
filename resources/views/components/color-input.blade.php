<style>
    .colour-picker-field {
        display: flex;
        /* justify-content: center; */
    }

    .colour-picker-field__item {
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .colour-picker-field__item--text {
        width: 85%;
        padding: 1rem;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .colour-picker-field__item--picker {
        width: 15%;
        height: auto;
        border-bottom-left-radius: 0;
        padding: 0;
        border: none;
        outline: none;
        box-shadow: none;
    }

    .colour-picker-field__item--picker:focus-visible,
    .colour-picker-field__item--text:focus-visible {
        outline: none;
    }
</style>
<div class="mt-2">

    <label for="{{$name}}">{{ $label }}</label>
    <div class="colour-picker-field">
        <input class="colour-picker-field__item colour-picker-field__item--text" type="text" {{ $attributes }}
            value="{{ $value ?? '' }}" />
        <input class="colour-picker-field__item--picker" type="color" name="{{ $name }}" id="{{$id}}"
            value="{{ $color ?? '#ffffff' }}">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const colourPickerFields = document.querySelectorAll('.colour-picker-field');

    colourPickerFields.forEach(item => {
        const text = item.querySelector('.colour-picker-field__item--text');
        const picker = item.querySelector('.colour-picker-field__item--picker');

        function handleSetColours(item1 = text, item2 = picker) {
            let colour = item1.value;
            item2.value = colour;
            text.style.border = `5px solid ${colour}`;
        }

        if (text.value) {
            handleSetColours();
        }

        text.addEventListener('change', () => {
            handleSetColours();
        });

        picker.addEventListener('input', () => {
            handleSetColours(picker, text);
        });
    });
});

</script>