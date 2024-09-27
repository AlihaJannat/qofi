<!-- resources/views/location.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Get Location</title>
</head>

<body>

  <h1>Get Current Location</h1>

  <button onclick="getLocation()">Get Location</button>

  <form id="locationForm" action="/save-location" method="POST" style="">
    @csrf
    <label for="latitude">Latitude</label>
    <input type="" id="latitude" name="latitude">
    <label for="longitude">Longitude</label>
    <input type="" id="longitude" name="longitude">
  </form>

  <script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function successCallback(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // Set the latitude and longitude values in the form
        document.getElementById('latitude').value = latitude;
        document.getElementById('longitude').value = longitude;

        // Submit the form
        // document.getElementById('locationForm').submit();
    }

    function errorCallback(error) {
        alert("Error getting location: " + error.message);
    }
  </script>

</body>

</html>