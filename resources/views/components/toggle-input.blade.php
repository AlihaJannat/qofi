<div>
    <div class="toggle-box">

        <span class="toggle-label-tag">{{ $label }}</span>
        <input type="checkbox" id="{{ $id }}" name="{{ $name }}" {{ $value ? "checked" : "" }} class="toggle-checkbox"
            onchange="this.nextElementSibling.classList.toggle('checked')">
        <label for="{{ $id }}" class="toggle-label {{ $value ? " checked" : "" }}">
            <span class="toggle-inner"></span>
            <span class="toggle-switch"></span>
        </label>
    </div>
</div>

<style>
    .toggle-box {
        display: flex;
        align-items: center;
    }

    .toggle-label-tag {
        margin-right: 3px;
    }

    .toggle-checkbox {
        display: none;
    }

    .toggle-label {
        display: inline-block;
        width: 50px;
        height: 25px;
        background-color: #ccc;
        border-radius: 34px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .toggle-inner {
        display: block;
        width: 100%;
        height: 100%;
        border-radius: 34px;
        transition: background-color 0.3s;
    }

    .toggle-switch {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s;
    }

    .toggle-checkbox:checked+.toggle-label {
        background-color: #4CAF50;
    }

    .toggle-checkbox:checked+.toggle-label .toggle-switch {
        transform: translateX(26px);
    }
</style>