document.addEventListener('DOMContentLoaded', (event) => {
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const priceLabel = document.getElementById('priceLabel');
    const rangeTrack = document.getElementById('rangeTrack');

    function updatePriceLabel() {
        const minValue = parseInt(minPrice.value);
        const maxValue = parseInt(maxPrice.value);
        priceLabel.textContent = `Price ${minValue} KD - ${maxValue} KD`;
        updateRangeTrack();
    }

    function updateRangeTrack() {
        const minValue = parseInt(minPrice.value);
        const maxValue = parseInt(maxPrice.value);
        const minPercent = (minValue / parseInt(minPrice.max)) * 100;
        const maxPercent = (maxValue / parseInt(maxPrice.max)) * 100;
        minPercentExclude = minPercent < 15 ? 1.5 : 0;
        maxPercentExclude = maxPercent > 85 ? 1 : 0;
        rangeTrack.style.left = `${minPercent + minPercentExclude}%`;
        rangeTrack.style.width = `${maxPercent - minPercent - maxPercentExclude - minPercentExclude}%`;
    }

    minPrice.addEventListener('input', (e) => {
        const minValue = parseInt(minPrice.value);
        const maxValue = parseInt(maxPrice.value);
        if (minValue >= maxValue) {
            minPrice.value = maxValue - 1;
        }
        updatePriceLabel();
    });

    maxPrice.addEventListener('input', (e) => {
        const minValue = parseInt(minPrice.value);
        const maxValue = parseInt(maxPrice.value);
        if (maxValue <= minValue) {
            maxPrice.value = minValue + 1;
        }
        updatePriceLabel();
    });

    updatePriceLabel();


    const minHeight = document.getElementById('minHeight');
    const maxHeight = document.getElementById('maxHeight');
    const heightLabel = document.getElementById('heightLabel');
    const rangeTrackHeight = document.getElementById('rangeTrackHeight');

    function updateHeightLabel() {
        const minValue = parseInt(minHeight.value);
        const maxValue = parseInt(maxHeight.value);
        heightLabel.textContent = `${minValue} cm - ${maxValue} cm`;
        updateRangeTrackHeight();
    }

    function updateRangeTrackHeight() {
        const minValue = parseInt(minHeight.value);
        const maxValue = parseInt(maxHeight.value);
        const minPercent = (minValue / parseInt(minHeight.max)) * 100;
        const maxPercent = (maxValue / parseInt(maxHeight.max)) * 100;
        minPercentExclude = minPercent < 15 ? 1.5 : 0;
        maxPercentExclude = maxPercent > 85 ? 1 : 0;
        rangeTrackHeight.style.left = `${minPercent + minPercentExclude}%`;
        rangeTrackHeight.style.width = `${maxPercent - minPercent - maxPercentExclude - minPercentExclude}%`;
    }

    minHeight.addEventListener('input', (e) => {
        const minValue = parseInt(minHeight.value);
        const maxValue = parseInt(maxHeight.value);
        if (minValue >= maxValue) {
            minHeight.value = maxValue - 1;
        }
        updateHeightLabel();
    });

    maxHeight.addEventListener('input', (e) => {
        const minValue = parseInt(minHeight.value);
        const maxValue = parseInt(maxHeight.value);
        if (maxValue <= minValue) {
            maxHeight.value = minValue + 1;
        }
        updateHeightLabel();
    });

    updateHeightLabel();
});


function filterCountries() {
    var input = document.getElementById('countrySearch').value.toLowerCase();

    var swatches = document.querySelectorAll('.country-div');

    swatches.forEach(function (swatch) {
        var countryName = swatch.getAttribute('data-country').toLowerCase();

        // Show or hide the swatch based on whether it matches the search input
        if (countryName.includes(input)) {
            swatch.style.display = 'flex'; // Show the swatch
        } else {
            swatch.style.display = 'none'; // Hide the swatch
        }
    });
}

function filterColors() {
    // Get input value and convert to lowercase for case-insensitive search
    var input = document.getElementById('colorSearch').value.toLowerCase();

    // Get all color swatches
    var swatches = document.querySelectorAll('.color-swatch');

    // Loop through each swatch and check if it matches the search input
    swatches.forEach(function (swatch) {
        var colorName = swatch.getAttribute('data-color').toLowerCase();

        // Show or hide the swatch based on whether it matches the search input
        if (colorName.includes(input)) {
            swatch.style.display = 'flex'; // Show the swatch
        } else {
            swatch.style.display = 'none'; // Hide the swatch
        }
    });
}

function toggleProductFilters() {
    const leftSide = document.getElementById('product-filter-side');

    leftSide.classList.toggle('hidden');
    leftSide.classList.toggle('fixed');
    leftSide.classList.toggle('inset-0');
}

function toggleSelectColor(color) {
    if (color.classList.contains('active')) {
        color.classList.remove('active');
    } else {
        color.classList.add('active');
    }
}