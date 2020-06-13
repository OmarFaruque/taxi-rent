<?php 
/*
* Booking Form
*/
?>

<div id="booking_form_wrap">
    <div id="tabs">
    <ul>
        <li><a href="#local_service"><?php _e('Local Service', 'taxi-rent'); ?></a></li>
        <li><a href="#tabs-2"><?php _e('Airport & Seaport', 'taxi-rent'); ?></a></li>
        <li><a href="#tabs-3"><?php _e('Hourly Rent', 'taxi-rent'); ?></a></li>
    </ul>
    <div id="local_service">
        <?php require_once($this->plugin_path . 'include/local-service-form.php'); ?>

    </div>
    <div id="tabs-2">
        <p>Demo...</p>
    </div>
    <div id="tabs-3">
        <p>Demo...</p>
    </div>
    </div>
</div>

<script>
  jQuery( function() {
    jQuery( "#tabs" ).tabs();
  } );
  </script>