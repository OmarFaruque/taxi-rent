<?php 
/*
* Booking Form
*/
?>

<div id="booking_form_wrap">
    <div id="tabs">
    <ul>
        <li><a href="#local_service"><?php _e('Local Service', 'taxi-rent'); ?></a></li>
        <li><a href="#airport-service"><?php _e('Airport & Seaport', 'taxi-rent'); ?></a></li>
        <li><a href="#tabs-3"><?php _e('Hourly Rent', 'taxi-rent'); ?></a></li>
    </ul>
    <div id="local_service">
        <?php require_once($this->plugin_path . 'include/local-service-form.php'); ?>
    </div>
    <div id="airport-service">
    <?php require_once($this->plugin_path . 'include/airport-service-form.php'); ?>
    </div>
    <div id="tabs-3">
        <p>Demo...</p>
    </div>
    </div>
</div>



<div id="map" style="display:none"></div>

<script>
var infowindow;

function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    mapTypeControl: false,
    center: {
      lat: -25.7234,
      lng: 28.4222
    },
    zoom: 14
  });
  infowindow = new google.maps.InfoWindow();

  new AutocompleteDirectionsHandler(map);
}

function AutocompleteDirectionsHandler(map) {
  this.map = map;
  this.originPlaceId = null;
  this.destinationPlaceId = null;
  this.travelMode = 'DRIVING';


  var originInput = document.getElementById('pickup'),
  destinationInput = document.getElementById('destination'),
  pickup_airport = document.getElementById('pickup_airport'),
  destination_airport = document.getElementById('destination_airport'),
  modeSelector = document.getElementById('mode-selector');
  this.directionsService = new google.maps.DirectionsService();
  this.directionsDisplay = new google.maps.DirectionsRenderer();
  this.directionsDisplay.setMap(map);

  var originAutocomplete = new google.maps.places.Autocomplete(
    originInput, {
      fields: ['place_id', 'name', 'types']
    });
  var destinationAutocomplete = new google.maps.places.Autocomplete(
    destinationInput, {
      fields: ['place_id', 'name', 'types']
    });

  this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
  this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');
}

// Sets a listener on a radio button to change the filter type on Places
// Autocomplete.
AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
  var me = this;
  autocomplete.bindTo('bounds', this.map);
  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.place_id) {
      window.alert("Please select an option from the dropdown list.");
      return;
    }
    if (mode === 'ORIG') {
      me.originPlaceId = place.place_id;
      localStorage.setItem("pickup_id", place.place_id);
    } else {
      me.destinationPlaceId = place.place_id;
      localStorage.setItem("destination_id", place.place_id);
    }
    me.route();
  });

};

AutocompleteDirectionsHandler.prototype.route = function() {
  if (!this.originPlaceId || !this.destinationPlaceId) {
    return;
  }
  var me = this;

  this.directionsService.route({
    origin: {
      'placeId': this.originPlaceId
    },
    destination: {
      'placeId': this.destinationPlaceId
    },
    travelMode: this.travelMode
  }, function(response, status) {
    if (status === 'OK') {
      
      document.getElementById('distance').value = response.routes[0].legs[0].distance.value;
      me.directionsDisplay.setDirections(response);
      var center = response.routes[0].overview_path[Math.floor(response.routes[0].overview_path.length / 2)];
      infowindow.setPosition(center);
      infowindow.setContent(response.routes[0].legs[0].duration.text + "<br>" + response.routes[0].legs[0].distance.text);
      infowindow.open(me.map);
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
};

jQuery( function() {
    jQuery( "#tabs" ).tabs();
});
</script>

<?php wp_enqueue_script( 'taxi-google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDIvHe8zwX9-D5YE39wEAqseTtsRP7EyvQ&libraries=places&callback=initMap', time(), true ); ?>
