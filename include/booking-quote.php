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

$distance = $_REQUEST['distance'];

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
                      <h2><?php echo $sv->post_title; ?></h2>
                      <div class="vacle-details">

                          <!-- Details -->
                          <div class="part-details">
                              <p class="text-center mb-0">
                              <span class="numberofpassenger">
                                <span class="number"><?php echo get_field('number_of_passengers', $sv->ID); ?></span>
                                <?php _e('Passengers', 'taxi-rent'); ?>
                              </span>
                              </p>
                              <p class="text-center">
                              <span class="numberofluges">
                                <span class="number"><?php echo get_field('number_of_luggage', $sv->ID); ?></span>
                                <?php _e('Luggage', 'taxi-rent'); ?>
                              </span>
                              </p>
                          </div>


                          <!-- Price -->
                          <div class="part-details">
                              <?php 
                                $firstMilePrice = 0;
                                $price = 0;
                                if($distance > 1000){
                                    $price =  
                                }
                              
                              ?>
                          </div>

                      </div>
                  </div>
               </div>
              <!-- End Description -->

          </div>
      <?php endforeach; ?>
  </div>
</div>




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