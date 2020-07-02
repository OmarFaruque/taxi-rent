<?php 
/*
* Taxi Quote Page
*/

$allvicles = array();

if(isset($_REQUEST['taxi_booking_nonce'])){
  $allvicles = get_posts(array(
    'post_type' => 'Vehicle', 
    'post_status' => 'publish'
  ));
}



// echo '<pre>';
// print_r($_REQUEST);
// echo '</pre>';

$collectionAddress = '';
$distinationAddress = '';
if(isset($_POST['pickup']))                     $collectionAddress = $_POST['pickup'];
if(isset($_POST['pickup_airport']))             $collectionAddress = $_POST['pickup_airport'];
if(isset($_POST['pickup_airport_drop']))        $collectionAddress = $_POST['pickup_airport_drop'];

// Distination
if(isset($_POST['destination']))               $distinationAddress = $_POST['destination'];
if(isset($_POST['destination_airport']))       $distinationAddress = $_POST['destination_airport'];
if(isset($_POST['destination_airport_drop']))  $distinationAddress = $_POST['destination_airport_drop'];

?>
<?php if(!isset($_REQUEST['submit_hourly'])): ?>
  <div id="map" style="width:100%; height:500px;"></div>
<?php endif; ?>
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
                              <p class="mb-0 text-center"><span class="price">$ <?php echo $this->vehicle_price($sv->ID); ?></span></p>
                              <p class="text-center mb-0"><span class="vatincluded text-center">(<?php echo sprintf('including %s vat', get_option('taxi_vat', 0) . '%'); ?>)</span></p>
                            </div>
                          </div>

                          <!-- Select Button -->
                          <div class="part-details select-button">
                              <a href="#" 
                                data-post_id = "<?php echo $sv->ID; ?>" 
                                data-baby_over_5="<?php echo get_field('baby_over_5', $sv->ID) ?  get_field('baby_over_5', $sv->ID) : 0; ?>" 
                                data-baby_under_5="<?php echo get_field('baby_under_5', $sv->ID); ?>" 
                                data-meet_n_greet="<?php echo get_field('meet_n_greet', $sv->ID); ?>" 
                                data-car_park="<?php echo get_field('car_park', $sv->ID); ?>" 
                                data-amount="<?php echo $this->vehicle_price($sv->ID); ?>" class="btn-taxi-rent btn btn-primar online-payment"><?php echo get_option( 'tr_car_select_btn', __('Select Car', 'taxi-rent') ); ?>
                              </a>
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

        <div class="part-one">
                  <h3><?php _e('Extra Options', 'taxi-rent'); ?></h3>
                  <p>
                    <?php _e('Add extra service with additional payment.', 'taxi-rent'); ?>
                  </p>
                  <form id="other_service" action="" method="post">
                     <div class="row">

                     <div class="col-sm-3 col-xs-3 col-md-3 text-center">
                          <div class="imageDiv">
                            <img src="<?php echo $this->plugin_url; ?>asset/img/baby-set.jpg" alt="<?php _e('Baby Seat', 'taxi-rent'); ?>">
                          </div>
                          <h6><?php _e('Baby Seat ( Child Over 5 Years)', 'taxi-rent'); ?>&nbsp;($<span class="baby_over_5"></span>)</h6>
                          <input name="baby_set_over_5" id="baby_set_over_5" type="checkbox" data-toggle="toggle" data-size="sm">
                      </div>
                      <div class="col-sm-3 col-xs-3 col-md-3 text-center">
                          <div class="imageDiv">    
                            <img src="<?php echo $this->plugin_url; ?>asset/img/baby-seat.jpg" alt="<?php _e('Baby Seat Under 5 years', 'taxi-rent'); ?>">
                          </div>
                          <h6><?php _e('Baby Seat ( Child Under 5 Years)', 'taxi-rent'); ?>&nbsp;($<span class="baby_under_5"></span>)</h6>
                          <input name="baby_set_under_5" id="baby_set_under_5" type="checkbox" data-toggle="toggle" data-size="sm">
                      </div>
                      <div class="col-sm-3 col-xs-3 col-md-3 text-center">
                          
                          <div class="imageDiv">
                            <img src="<?php echo $this->plugin_url; ?>asset/img/meet-and-greet-banner01.png" alt="<?php _e('Meet & Greet', 'taxi-rent'); ?>">
                          </div>
                          <h6><?php _e('Meet & Greet', 'taxi-rent'); ?>&nbsp;($<span class="meet_n_greet"></span>)</h6>
                          <input name="meet_n_greet" id="meet_n_greet" type="checkbox" data-toggle="toggle" data-size="sm">
                      </div>
                      <div class="col-sm-3 col-xs-3 col-md-3 text-center">
                            <div class="imageDiv">
                              <img src="<?php echo $this->plugin_url; ?>asset/img/car-park.png" alt="<?php _e('Car Park', 'taxi-rent'); ?>">
                            </div>
                          
                            <h6><?php _e('Car Park', 'taxi-rent'); ?>&nbsp;($<span class="car_park"></span>)</h6>
                            <input name="car_park" id="car_park" type="checkbox" data-toggle="toggle" data-size="sm">
                      </div>

                      </div>
                      <!-- Button and Display Payment  -->
                      <div id="displaypayment">
                          <div class="row mt-5">
                            <div class="col-md-6 col-xs-12 col-sm-6">
                              <button class="btn btn-primary booking-form next" type="submit"><?php _e('Next', 'taxi-rent' ) ?></button>
                            </div>
                            <div class="col-md-6 col-xs-12 col-sm-6 text-right">
                                <h3>
                                  <strong><?php _e('Price', 'taxi-rent'); ?>: </strong>
                                  <strong class="priceAfterAddService"></strong>
                                </h3>
                            </div>
                          </div>
                      </div>
                     
                  </form>
        </div>
        <div class="part-tow d-none">
        <form id="comfirmByPayment" action="" method="post">
              <h3><?php _e('Booking Online Single Trip', 'taxi-rent'); ?></h3>    
              <p class="details"><?php _e('Please provide us with full additional information about the passenger and the journey', 'taxi-rent'); ?></p>

              <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label for="booking_person"><?php _e('Person making the reservation', 'taxi_rent'); ?>*</label>
                      <input type="text" required name="booking_person" id="booking_person" class="form-control">
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
                      <label for="contact_number"><?php _e('Contact Number', 'taxi_rent'); ?>*</label>
                      <input required type="tel" name="contact_number" id="contact_number" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="contact_email"><?php _e('Your E-mail Address', 'taxi_rent'); ?>*</label>
                      <input required type="mail" name="contact_email" id="contact_email" class="form-control">
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
                      <input required type="text" name="passenger_name" id="passenger_name" class="form-control">
                    </div>
                </div>

                <!-- Passanger Contact Number -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="passenger_contact_no"><?php _e('Passenger Contact No*', 'taxi_rent'); ?></label>
                      <input required type="tel" name="passenger_contact_no" id="passenger_contact_no" class="form-control">
                    </div>
                </div>

                <!-- Number of Passengert -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="number_of_passenger"><?php _e('Number of Passenger*', 'taxi_rent'); ?></label>
                      <input required type="number" name="number_of_passenger" id="number_of_passenger" class="form-control">
                    </div>
                </div>

                <!-- Number of luggage -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="number_of_luggage"><?php _e('Number of Luggage*', 'taxi_rent'); ?></label>
                      <input required type="number" name="number_of_luggage" id="number_of_luggage" class="form-control">
                    </div>
                </div>
              </div>

              <hr>
              <h4><?php _e('Travel Details', 'text-rent'); ?></h4>
              <div class="row">

                <!-- Travel Date -->
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label for="travel_date_time"><?php _e('Travel Date*', 'taxi_rent'); ?></label>
                      <input type="text" autocomplete="off" name="travel_date_time" id="travel_date_time" class="form-control">
                    </div>
                </div>

                <!-- Collection Address -->
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label for="collection_address"><?php _e('Collection Address', 'taxi_rent'); ?></label>
                      <textarea readonly name="collection_address" id="collection_address" class="form-control" cols="30" rows="2"><?php echo $collectionAddress; ?></textarea>
                    </div>
                </div>

                <!-- Destination  Address -->
                <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label for="destination_address"><?php _e('Destination Address', 'taxi_rent'); ?></label>
                      <textarea readonly name="destination_address" id="destination_address" class="form-control" cols="30" rows="2"><?php echo $distinationAddress; ?></textarea>
                    </div>
                </div>


                <!-- Airport & terminal (if applicable) -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="airport_terminal"><?php _e('Airport & terminal (if applicable)', 'taxi_rent'); ?></label>
                      <input type="text" name="airport_terminal" id="airport_terminal" class="form-control">
                    </div>
                </div>

                <!-- Flight number ( if known ) -->
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label for="flight_number"><?php _e('Flight number ( if known )', 'taxi_rent'); ?></label>
                      <input type="text" name="flight_number" id="flight_number" class="form-control">
                    </div>
                </div>

                 <!-- Special request -->
                 <div class="col-md-12 col-xs-12">
                    <div class="form-group">
                      <label for="special_request"><?php _e('Special request', 'taxi_rent'); ?></label>
                      <textarea name="special_request" id="special_request" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>

                <div class="col-md-12 col-xs-12">
                  <input name="taxi_rent_amount" type="hidden"/>
                  <input name="vehicle_id" type="hidden"/>
                  <div class="btn-group" role="group" aria-label="First group">
                      <button class="btn btn-secondary backtochoose" type="submit"><?php _e('<< Back', 'taxi-rent'); ?></button>
                      <input type="submit" class="btn btn-secondary" name="pay_now" id="pay_now" value="<?php _e('Pay Now', 'texi-rent'); ?>">
                      <input type="submit" class="btn btn-secondary" name="pay_later" id="pay_later" value="<?php _e('Pay Later', 'texi-rent'); ?>">
                  </div>
                </div>



              </div>
        </form>
        </div> <!-- End Part-Two -->
  </div>
</div>

<!-- End Online Booking Form -->




<?php if(!isset($_REQUEST['submit_hourly'])): ?>
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
  this.origin = null;
  this.destination = null;


  <?php if(isset($_POST['pickup_airport_drop'])  && $_POST['pickup_airport_drop'] != '' ){ ?>
    this.origin = "<?php echo $_POST['pickup_airport_drop']; ?>";
  <?php }elseif(isset($_POST['pickup_airport'])  && $_POST['pickup_airport'] != '' ){ ?>
    this.origin = "<?php echo $_POST['pickup_airport']; ?>";
  <?php }elseif(isset($_POST['pickup'])  && $_POST['pickup'] != '' ){ ?>
    this.origin = "<?php echo $_POST['pickup']; ?>";
  <?php } ?>

  <?php if(isset($_POST['destination_airport_drop'])  && $_POST['destination_airport_drop'] != '' ){ ?>
    this.destination = "<?php echo $_POST['destination_airport_drop']; ?>";  
  <?php }elseif(isset($_POST['destination_airport'])  && $_POST['destination_airport'] != '' ){ ?>
    this.destination = "<?php echo $_POST['destination_airport']; ?>";  
  <?php }elseif(isset($_POST['destination'])  && $_POST['destination'] != '' ){ ?>
    this.destination = "<?php echo $_POST['destination']; ?>";  
  <?php } ?>


  this.travelMode = 'DRIVING';
  
  this.directionsService = new google.maps.DirectionsService();
  this.directionsDisplay = new google.maps.DirectionsRenderer();
  this.directionsDisplay.setMap(map);

  var me = this;
  me.route();
}


AutocompleteDirectionsHandler.prototype.route = function() {
  if (!this.origin || !this.destination) {
    return;
  }

  console.log(this.origin);
  var me = this;
  var waypts = [];
  
  <?php if(isset($_REQUEST['drop_off']) && !empty($_REQUEST['drop_off'])): ?>
  waypts.push({
              location: "<?php echo $_REQUEST['drop_off']; ?>",
              stopover: true
  });
  <?php endif; ?>

  this.directionsService.route({
    origin: this.origin,
    waypoints: waypts,
    optimizeWaypoints: true,
    destination: this.destination,
    travelMode: this.travelMode
  }, function(response, status) {

    // console.log(response);
    if (status === 'OK') {
      me.directionsDisplay.setDirections(response);
      var center = response.routes[0].overview_path[Math.floor(response.routes[0].overview_path.length / 2)];
      infowindow.setPosition(center);

      var distance1 = response.routes[0].legs[0].distance.value / 1609.34;

      var content = response.routes[0].legs[0].duration.text + "<br>" + distance1.toFixed(2) + ' Miles';
      if(response.routes[0].legs[1]){
        var distance2 = response.routes[0].legs[1].distance.value / 1609.34;
        content += '<hr/>' + response.routes[0].legs[1].duration.text + "<br>" + distance2.toFixed(2) + ' Miles';
      }

      infowindow.setContent(content);
      infowindow.open(me.map);
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
};

</script>

<?php 

wp_enqueue_script( 'taxi-google-map', 'https://maps.googleapis.com/maps/api/js?key='.get_option('map_api', 'AIzaSyDIvHe8zwX9-D5YE39wEAqseTtsRP7EyvQ').'&libraries=places&callback=initMap', time(), true ); 
wp_enqueue_style( 'bootstrap-css-toggle', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css', array(), true, 'all' );
wp_enqueue_script( 'bootstrap-js-toggle', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js', array(), true );
wp_enqueue_script( 'jquery-form-validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js', array('jquery'), time(), true ); 
?>
<?php endif; ?>