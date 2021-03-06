<?php 
/*
* Booking Form
*/


// echo 'Coockis array <br/><pre>';
// print_r($_COOKIE);
// echo '</pre>';

// echo 'Coockis array <br/><pre>';
// print_r($_SESSION);
// echo '</pre>';


?>

<div id="booking_form_wrap">
    <div id="tabs">
    <ul>
        <?php if(get_option( 'local_service', 1 )): ?>
          <li><a href="#local_service"><?php _e('Local Service', 'taxi-rent'); ?></a></li>
        <?php endif; ?>
        <?php if(get_option( 'airport_seaport', 1 )): ?>
          <li><a href="#airport-service"><?php _e('Airport & Seaport', 'taxi-rent'); ?></a></li>
        <?php endif; ?>
        <?php if(get_option( 'hourly_rent', 1 )): ?>
        <li><a href="#hourly"><?php _e('Hourly Rent', 'taxi-rent'); ?></a></li>
        <?php endif; ?>
    </ul>
    <!-- End Tabls  -->

    <?php if(get_option( 'local_service', 1 )): ?>
      <div id="local_service">
          <?php require_once($this->plugin_path . 'include/local-service-form.php'); ?>
      </div>
    <?php endif; ?>


    <!-- Airport and Seaport Service -->
    <?php if(get_option( 'airport_seaport', 1 )): ?>
      <div id="airport-service">
      <?php require_once($this->plugin_path . 'include/airport-service-form.php'); ?>
      </div>
    <?php endif; ?>


    <!-- Hourly Rent Section  -->
    <?php if(get_option( 'hourly_rent', 1 )): ?>
      <div id="hourly">
          <div id="hourlyForm">
            <form method="post" action="<?php echo get_the_permalink( get_option('quote_page') ); ?>" id="hourlyF">
            <?php wp_nonce_field( 1, 'taxi_booking_nonce' ); ?>
                <div class="form-group">
                    <label for="hours"><?php _e('Hours', 'taxi-rent'); ?></label>
                    <input type="number" step="1" min="1" name="hours" id="hours" class="form-control">
                </div>

                <br>
                <input name="submit_hourly" type="submit" value="<?php echo get_option( 'tr_from_button_text', __('Show price & book online', 'taxi-rent') ); ?>" class="btn btn-primary">
            </form>
          </div>
      </div>
    <?php endif; ?>
    <!-- End Tab Content -->
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
  var me = this;
  this.map = map;
  this.originPlaceId = null;
  this.destinationPlaceId = null;
  this.airportPickup = null;
  this.airportDistination = null;
  this.travelMode = 'DRIVING';


  var originInput         = document.getElementById('pickup'),
  destinationInput        = document.getElementById('destination'),
  pickup_airport          = document.getElementById('pickup_airport'),
  drop_off                = document.getElementById('drop_off'),
  
  destination_airport     = document.getElementById('destination_airport'),
  modeSelector            = document.getElementById('mode-selector');
  this.directionsService  = new google.maps.DirectionsService();
  this.directionsDisplay  = new google.maps.DirectionsRenderer();
  this.directionsDisplay.setMap(map);


  // Regular Form
  var originAutocomplete = new google.maps.places.Autocomplete(
    originInput, {
      fields: ['place_id', 'name', 'types']
    });
  
  
  var drop_off_Autocomplete = new google.maps.places.Autocomplete(
    drop_off, {
      fields: ['place_id', 'name', 'types']
  });

  
  var destinationAutocomplete = new google.maps.places.Autocomplete(
    destinationInput, {
      fields: ['place_id', 'name', 'types']
  });







  // Airport & Seaport
  var airportPickupAutocomplete = new google.maps.places.Autocomplete(
    pickup_airport, {
      fields: ['place_id', 'name', 'types']
  });


 
  // Airport & Seaport
  var airportDestinationAutocomplete = new google.maps.places.Autocomplete(
    destination_airport, {
      fields: ['place_id', 'name', 'types']
  });

  this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
  this.setupPlaceChangedListener(drop_off_Autocomplete, 'DROP');
  this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');


  
  jQuery(document.body).on('change', 'input.drop_off_port, select[name="destination_airport"], input[name="destination_airport_drop"], input[name="pickup_airport_drop"], select[name="pickup_airport"]', function(){
    // Search for Google's office in Australia.
    
    setTimeout(() => {      
      execute = false;

      // console.log('status: ' + jQuery('input[name="swap"]').val());
      if(jQuery('input[name="swap"]').val() == 'town_to_port'){
        var pickup = jQuery('select#pickup_airport_select option:selected').text();
        if(jQuery('input[name="pickup_airport_drop"]').val() != '') pickup = jQuery('input[name="pickup_airport_drop"]').val();

        var port_distination = jQuery('select#destination_airport_select option:selected').text();
        if(jQuery('select#destination_airport_select').val() !='' && jQuery('select#pickup_airport_select').val() !='') execute = true;
      }else{
        var pickup = jQuery('select[name="destination_airport"] option:selected').text();
        
        var port_distination = jQuery('select[name="pickup_airport"] option:selected').text();
        if(jQuery('input[name="pickup_airport_drop"]').val() != '') port_distination = jQuery('input[name="pickup_airport_drop"]').val();

        if( jQuery('select[name="pickup_airport"]').val() != '' && pickup !='') execute = true; 
      }

      if(execute && pickup != '' && port_distination != ''){
        me.airportPickup = pickup;
        me.airportDistination = port_distination;
        me.airputroute();
      }

    }, 500); 
  
  });
}



AutocompleteDirectionsHandler.prototype.airputroute = function() {
  // console.log(localStorage.getItem('destination_id'));
  if (!this.airportPickup || !this.airportDistination) {
    return;
  }
  var me = this,
  waypts = [];
  var dropLength = document.getElementsByClassName('drop_off_port');
  
  if(dropLength.length > 0){
    for(var i=0; i < dropLength.length; i++ ){
      waypts.push({
                  location: dropLength[i].value,
                  stopover: true
      });
    }
  }


  // Add waypoints if pickup drop-off is not empty
  
  var process = true;
  if(document.getElementById('pickup_airport').value != ''){
      this.directionsService.route({
      origin: document.getElementById('pickup_airport_select').value,
      destination: document.getElementById('pickup_airport').value,
      travelMode: this.travelMode,
      optimizeWaypoints: true,
    }, function(response, status){
        var dropofvalue = response.routes[0].legs[0].distance.value;
        if(dropofvalue > 2000){
          process = false;
          jQuery('form#portForm').find('input[type="submit"]').prop('disabled', true);
        }
    });    
  }




  this.directionsService.route({
    origin: this.airportPickup,
    destination: this.airportDistination,
    travelMode: this.travelMode,
    waypoints: waypts,
    optimizeWaypoints: true,
  }, function(response, status) {

    if (status === 'OK' && process) {
      var distance = response.routes[0].legs[0].distance.value;
      if(response.routes[0].legs[1]){
        distance += response.routes[0].legs[1].distance.value;
      }
      if(response.routes[0].legs[2]){
        distance += response.routes[0].legs[2].distance.value;
      }
      if(response.routes[0].legs[3]){
        distance += response.routes[0].legs[3].distance.value;
      }

      jQuery('form#portForm').find('input[name="distance"]').val(distance);
      jQuery('form#portForm').find('input[type="submit"]').prop('disabled', false);
    } else {
      window.alert('The area is outside 2miles from your address please choose area which is near to you.');
    }
  });
};

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
    }
    else if(mode == 'DROP'){
      document.getElementById('drop_off_place_id').value = place.place_id;
    }
    else {
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
  var waypts = [];

 
  var dropLength = document.getElementsByClassName('drop_off'); 
  if(dropLength.length > 0){
    for(var i=0; i < dropLength.length; i++ ){
      waypts.push({
                  location: dropLength[i].value,
                  stopover: true
      });
    }
  }


  this.directionsService.route({
    origin: {
      'placeId': this.originPlaceId
    },
    waypoints: waypts,
    optimizeWaypoints: true,
    destination: {
      'placeId': this.destinationPlaceId
    },
    travelMode: this.travelMode
  }, function(response, status) {
    if (status === 'OK') {
      
      var kilomiter = response.routes[0].legs[0].distance.value;

      if(response.routes[0].legs[1]){
        kilomiter += response.routes[0].legs[1].distance.value;
      }
      if(response.routes[0].legs[2]){
        kilomiter += response.routes[0].legs[2].distance.value;
      }
      if(response.routes[0].legs[3]){
        kilomiter += response.routes[0].legs[3].distance.value;
      }
      
      document.getElementById('distance').value = kilomiter;
      me.directionsDisplay.setDirections(response);
      var center = response.routes[0].overview_path[Math.floor(response.routes[0].overview_path.length / 2)];
      infowindow.setPosition(center);
      var content = response.routes[0].legs[0].duration.text + "<br>" + response.routes[0].legs[0].distance.text;
      
      if(response.routes[0].legs[1]){
        content += '<hr/>' + response.routes[0].legs[1].duration.text + "<br>" + response.routes[0].legs[1].distance.text;
      }
      if(response.routes[0].legs[2]){
        content += '<hr/>' + response.routes[0].legs[2].duration.text + "<br>" + response.routes[0].legs[2].distance.text;
      }
      if(response.routes[0].legs[3]){
        content += '<hr/>' + response.routes[0].legs[3].duration.text + "<br>" + response.routes[0].legs[3].distance.text;
      }

      infowindow.setContent(content);

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

<?php 
  wp_enqueue_script( 'taxi-google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDIvHe8zwX9-D5YE39wEAqseTtsRP7EyvQ&libraries=places&callback=initMap', time(), true ); 
  wp_enqueue_style( 'fontAwesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), time(), 'all' );
  wp_enqueue_script( 'jquery-form-validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js', array('jquery'), time(), true ); 
?>
