function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    L.marker([position.coords.latitude, position.coords.longitude]).addTo(mymap)
            .bindPopup("Browser Location").openPopup();
    mymap.panTo(new L.LatLng(position.coords.latitude, position.coords.longitude));
}

//$(document).ready(function () {
    var lat = parseFloat($('#lat').val());
    var lon = parseFloat($('#lon').val());
    var mymap = L.map('mapid').setView([lat, lon], 5);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1
    }).addTo(mymap);

    //geo data returned
    if (lat !== 0) {
        L.marker([lat, lon]).addTo(mymap).bindPopup("IP Location").openPopup();
        mymap.panTo(new L.LatLng(lat, lon));
    }
    
    //no geo data returned
    if (lat === 0) {
        getLocation();
    }

    var popup = L.popup();
//});


