// This file contains JavaScript code for displaying and interacting with maps, likely using a mapping library.

function initMap() {
    // Initialize the map and set its options
    const mapOptions = {
        center: { lat: 15.8801, lng: 108.3259 }, // Coordinates for Hoi An
        zoom: 12,
        mapTypeId: 'roadmap'
    };

    // Create a new map instance
    const map = new google.maps.Map(document.getElementById('map'), mapOptions);

    // Add a marker to the map
    const marker = new google.maps.Marker({
        position: { lat: 15.8801, lng: 108.3259 },
        map: map,
        title: 'Hoi An'
    });
}

// Load the Google Maps API script dynamically
function loadScript() {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap`;
    script.async = true;
    document.body.appendChild(script);
}

// Call the loadScript function to load the Google Maps API
loadScript();