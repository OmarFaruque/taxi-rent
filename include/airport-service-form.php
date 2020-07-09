<?php 

/*
* Local Service Form
*/
$quote_url = get_the_permalink( get_option('quote_page') );

?>

<form id="portForm" action="<?php echo $quote_url; ?>" method="post">

    <div id="switchDirection" class="switcher mb-2">
        <input type="hidden" name="swap" value="town_to_port">
        <span><?php _e('Click to swap Pickup', 'taxi-rent'); ?>:</span>
        <img src="<?php echo $this->plugin_url; ?>asset/img/noun_swap_373742.png" alt="Switcher">
    </div>

    <?php wp_nonce_field( 1, 'taxi_booking_nonce' ); ?>
    <div id="townSelection" class="form-group">
        <select name="pickup_airport" class="form-control" id="pickup_airport_select">
            <option value=""><?php _e('Please Select Your Town', 'taxi-rent'); ?></option>
            <?php 
            if(get_option('portlists')):
                $portlists = json_decode(get_option('portlists'));

                // echo 'omar array <br/><pre>';
                // print_r($portlists);
                // echo '</pre>';
                foreach($portlists as $k => $sport){
                    echo '<option value="'.$sport->port_a.'">'.$sport->port_a.'</option>';
                }    
            endif; 
            
            ?>
        </select>
        <div class="selectlocation position-relative mt-2">
            <input type="text" placeholder="<?php _e('Please Enter your Address', 'taxi-rent'); ?>" class="w-100 form-control" name="pickup_airport_drop" id="pickup_airport">
            <i class="fa fa-map-marker"></i>
        </div>
    </div>




   <div id="dropAreea">
        <!-- Dropof -->
        <div class="form-group" id="stop_address" style="display:none;">
            
        </div>

        <div class="adddropoff mt-2 mb-2">
            <span class="addDropOffButton">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </span>
            <span class="text"><?php _e('VIA', 'taxi-rent'); ?></span>
        </div>
        <!-- End Dropof -->
   </div>




    <div id="portListsArea" class="form-group">
        <!-- <input type="text" class="w-100" name="destination_airport" id="destination_airport"> -->
        <select name="destination_airport" class="form-control w-100" id="destination_airport_select">
            <option value=""><?php _e('Please Select Airport or Seaport', 'taxi-rent'); ?></option>
            <?php 
            if(get_option('portlists')):
                $portlists = json_decode(get_option('portlists'));
                foreach($portlists as $k => $sport){
                    echo '<option value="'.$sport->port_b.'">'.$sport->port_b.'</option>';
                }    
            endif; 
            
            ?>
        </select>
    </div>
    <div class="form-group mt-1" id="way">
        <label for="one_way">
            <input type="radio" name="way" id="one_way" checked value="1">
            <?php _e('One Way', 'taxi-rent'); ?>
        </label>
        <label for="return_way">
            <input type="radio" name="way" id="return_way" value="2">
            <?php _e('Return', 'taxi-rent'); ?>
        </label>
    </div>
<br>
<input type="hidden" id="distance" name="distance" value="">
<input type="submit" disabled class="btn btn-primary" name="submit_for_quote" value="<?php echo get_option( 'tr_from_button_text', __('Show price & book online', 'taxi-rent') ); ?>">
</form>
