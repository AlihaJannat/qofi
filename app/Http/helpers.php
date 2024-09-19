<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonInterval;

function upload_image($image, $path)
{
    // Check if the directory exists, if not, create it
    if (!file_exists(public_path($path))) {
        mkdir(public_path($path), 0755, true);
    }

    $fileName = time() . rand(1, 999) . '_' . Str::random(45) . "." . $image->getClientOriginalExtension();
    $image->move(public_path($path), $fileName);

    return $fileName;
}

function copy_image($imagePath, $destinationPath)
{
    // Check if the directory exists, if not, create it
    if (!file_exists(public_path($destinationPath))) {
        mkdir(public_path($destinationPath), 0755, true);
    }

    // Get the file extension
    $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

    // Generate a new random file name
    $fileName = time() . rand(1, 999) . '_' . Str::random(45) . '.' . $fileExtension;

    // Determine the new file path
    $newFilePath = public_path($destinationPath) . '/' . $fileName;

    // Copy the file
    if (!copy($imagePath, $newFilePath)) {
        throw new Exception("Failed to copy $imagePath to $newFilePath");
    }

    return $fileName;
}

function delete_image($image_name, $path)
{
    if (File::exists(public_path($path . $image_name))) {
        File::delete(public_path($path . $image_name));
    }
}

function createSlug($string)
{
    // Convert the string to lowercase
    $slug = strtolower($string);

    // Replace any special characters
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

    // Replace multiple spaces or hyphens with a single hyphen
    $slug = preg_replace('/[\s-]+/', ' ', $slug);

    // Replace spaces with hyphens
    $slug = preg_replace('/\s/', '-', $slug);

    // Trim hyphens from the start and end of the slug
    $slug = trim($slug, '-');

    return $slug;
}

function replaceHyphenAndCapitalize($string)
{
    // Replace hyphens with spaces
    $string = str_replace('-', ' ', $string);

    // Convert the string to lowercase
    $string = strtolower($string);

    // Capitalize the first letter of each word
    $string = ucwords($string);

    return $string;
}

function convertDaysToMonths($days)
{
    $daysInMonth = 30;
    // Calculate the number of months and remaining days
    $months = intdiv($days, $daysInMonth); // Full months
    $remainingDays = $days % $daysInMonth; // Remaining days

    // Build the result string
    $result = '';

    if ($months > 0) {
        $result .= $months . ' month' . ($months > 1 ? 's' : '');
    }

    if ($remainingDays > 0) {
        if ($months > 0) {
            $result .= ' and ';
        }
        $result .= $remainingDays . ' day' . ($remainingDays > 1 ? 's' : '');
    }

    if ($result === '') {
        $result = '0 days';
    }

    return $result;
}

function calculateCharges($cart)
{

    $result = [];
    $totalAmount = 0;
    $totalDeliveryCharges = 0;
    $totalProducts = 0;
    $totalVatAmount = 0;
    $vatRate = config('constant.VATPERCENTAGE');


    foreach ($cart as $shopSlug => $products) {
        $shopTotalAmount = 0;
        $shopTotalProducts = 0;
        $shopDeliveryCharges = 0;

        // Calculate the total amount and total products for this shop
        foreach ($products as $product) {
            $shopTotalAmount += $product['price'] * $product['quantity'];
            $shopTotalProducts += $product['quantity'];
        }

        // Calculate VAT for this shop
        $shopVatAmount = $shopTotalAmount * $vatRate;

        // Determine delivery charges based on delivery type
        $deliveryType = $products[array_key_first($products)]['shop']['delivery_type'];
        $amountLimit = $products[array_key_first($products)]['shop']['amount_limit'];
        $shopDeliveryChargesSetting = $products[array_key_first($products)]['shop']['delivery_charges'];

        if ($deliveryType == 'flat_charges') {
            $shopDeliveryCharges = $shopDeliveryChargesSetting;
        } elseif ($deliveryType == 'over_amount' && $shopTotalAmount < $amountLimit) {
            $shopDeliveryCharges = $shopDeliveryChargesSetting;
        } elseif ($deliveryType == 'free_delivery') {
            $shopDeliveryCharges = 0;
        }

        // Accumulate shop totals
        $totalAmount += $shopTotalAmount;
        $totalDeliveryCharges += $shopDeliveryCharges;
        $totalProducts += $shopTotalProducts;
        $totalVatAmount += $shopVatAmount;

        // Store shop data in result array
        $result['shops'][$shopSlug] = [
            'shop_total_amount' => $shopTotalAmount,
            'shop_vat_amount' => $shopVatAmount,
            'shop_delivery_charges' => $shopDeliveryCharges,
            'shop_total_products' => $shopTotalProducts,
        ];
    }

    // Add overall totals to the result
    $result['total_vat_amount'] = $totalVatAmount;
    $result['total_delivery_charges'] = $totalDeliveryCharges;
    $result['sub_total'] = $totalAmount;
    $result['total_amount'] = $totalAmount + $totalVatAmount + $totalDeliveryCharges;
    $result['total_products'] = $totalProducts;

    return $result;
}


function getCurrentDateTimeInfo()
{
    // Get the current date in DD-MM-YYYY format
    $currentDate = Carbon::now()->format('d-m-Y');

    // Get the current time in HH:MM:SS format
    $currentTime = Carbon::now()->toTimeString();

    // Get the current day (e.g., Monday)
    $currentDay = Carbon::now()->format('l');

    // Return the array with date, time, and day
    return [
        'date' => $currentDate,
        'time' => $currentTime,
        'day' => $currentDay,
    ];
}

function getCartProducts($cart)
{
    $products = [];

    foreach ($cart as $shop => $items) {
        foreach ($items as $key => $item) {
            $products[] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'options' => ''
            ];
        }
    }
    return $products;
}


function app_setting($key, $default = null)
{
    return app('app_settings')[$key] ?? $default;
}
