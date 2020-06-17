<?php 
/*
* Taxi Quote Page
*/

$allvicles = array();

if(isset($_REQUEST['taxi_booking_nonce'])){
  $allvicles = get_posts(array(
    'post_type' => 'vichle', 
    'post_status' => 'publish'
  ));
}



echo '<pre>';
print_r($_REQUEST);
echo '</pre>';

?>

<div id="map" style="width:100%; height:500px;"></div>
<br>
<div id="vaclelist">
  <div class="inner-bacle-list">
      <?php foreach($allvicles as $sv): ?>
          <div class="single-vicle">
              <!-- Image -->
              <div class="image-vicle">
                <?php if(has_post_thumbnail( $sv->ID )): ?>
                  <?php echo get_the_post_thumbnail( $sv->ID, 'full', array('class' => 'vicle-image') ); ?>
                <?php else: ?>
                  <img src="<?php echo $this->plugin_url; ?>asset/img/demo.jpg" alt="<?php _e('Demo Vacle', 'taxi-rent'); ?>">
                <?php endif; ?>
              </div>
              <!-- End Image -->
              <!-- Start Description -->
               <div class="description-desc">
                  <div class="innerdesc">
                      <h4><?php echo $sv->post_title; ?></h4>
                      <div class="vacle-details">

                          <!-- Details -->
                          <div class="part-details">
                              <div class="taxi-shadow">
                              <p class="text-center mb-0">
                              <span class="numberofpassenger">
                                <span class="number"><?php echo get_field('number_of_passengers', $sv->ID); ?></span>
                                <?php _e('Passengers', 'taxi-rent'); ?>
                              </span>
                              </p>
                              <p class="text-center mb-0">
                              <span class="numberofluges">
                                <span class="number"><?php echo get_field('number_of_luggage', $sv->ID); ?></span>
                                <?php _e('Luggage', 'taxi-rent'); ?>
                              </span>
                              </p>

                              </div>
                          </div>


                          <!-- Price -->
                          <div class="part-details price">
                            <div class="taxi-shadow">
                              <p class="mb-0 text-center"><span class="price">$ <?php echo $this->vichle_price($sv->ID); ?></span></p>
                              <p class="text-center mb-0"><span class="vatincluded text-center">(<?php echo sprintf('including %s vat', get_option('taxi_vat', 0) . '%'); ?>)</span></p>
                            </div>
                          </div>

                          <!-- Select Button -->
                          <div class="part-details select-button">
                              <a href="#" class="btn-taxi-rent btn btn-primar online-payment"><?php _e('Select Car', 'webinar'); ?></a>
                          </div>

                      </div>
                  </div>
               </div>
              <!-- End Description -->

          </div>
      <?php endforeach; ?>
  </div>
</div>


<!-- Online Booking Form  -->
<div id="online-booking-form" title="<?php _e('Booking Online', 'taxi-rent'); ?>">
  <div class="form-online-inner">
        <form action="" method="post">
              <h3><?php _e('Booking Online Single Trip', 'taxi-rent'); ?></h3>    
              <p class="details"><?php _e('Please provide us with full additional information about the passenger and the journey', 'taxi-rent'); ?></p>

              <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label for="booking_person"><?php _e('Person making the reservation', 'taxi_rent'); ?></label>
                      <input type="text" name="booking_person" id="booking_person" class="form-control">
                    </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="company_name"><?php _e('Company Name', 'taxi_rent'); ?></label>
                      <input type="text" name="company_name" id="company_name" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="contact_number"><?php _e('Contact Number', 'taxi_rent'); ?></label>
                      <input type="tel" name="contact_number" id="contact_number" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="contact_email"><?php _e('Your E-mail Address', 'taxi_rent'); ?></label>
                      <input type="mail" name="contact_email" id="contact_email" class="form-control">
                    </div>
                  </div>
              </div>

              <hr>
              <h4><?php _e('Passenger Information', 'text-rent'); ?></h4>
              <div class="row">

                <!-- Passanger Name -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="passenger_name"><?php _e('Lead passenger*', 'taxi_rent'); ?></label>
                      <input type="text" name="passenger_name" id="passenger_name" class="form-control">
                    </div>
                </div>

                <!-- Passanger Contact Number -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="passenger_contact_no"><?php _e('Passenger Contact No*', 'taxi_rent'); ?></label>
                      <input type="tel" name="passenger_contact_no" id="passenger_contact_no" class="form-control">
                    </div>
                </div>

                <!-- Number of Passengert -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="number_of_passenger"><?php _e('Number of Passenger*', 'taxi_rent'); ?></label>
                      <input type="text" name="number_of_passenger" id="number_of_passenger" class="form-control">
                    </div>
                </div>

              </div>
        </form>
  </div>
</div>

<!-- End Online Booking Form -->





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
  this.originPlaceId = (localStorage.getItem('pickup_id')) ? localStorage.getItem('pickup_id') : null;
  this.destinationPlaceId = (localStorage.getItem('destination_id')) ? localStorage.getItem('destination_id') : null;
  this.travelMode = 'DRIVING';
  
  this.directionsService = new google.maps.DirectionsService();
  this.directionsDisplay = new google.maps.DirectionsRenderer();
  this.directionsDisplay.setMap(map);

  var me = this;
  me.route();
}


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

    console.log(response.routes[0].legs[0].distance.value);
    if (status === 'OK') {
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

</script>

<?php wp_enqueue_script( 'taxi-google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDIvHe8zwX9-D5YE39wEAqseTtsRP7EyvQ&libraries=places&callback=initMap', time(), true ); ?>