<?php 

/*
* Local Service Form
*/
$quote_url = get_the_permalink( get_option('quote_page') );

?>

<form id="portForm" action="<?php echo $quote_url; ?>" method="post">

    <div id="switchDirection" class="switcher mb-2">
        <img src="<?php echo $this->plugin_url; ?>asset/img/noun_swap_373742.png" alt="Switcher">
    </div>

    <?php wp_nonce_field( 1, 'taxi_booking_nonce' ); ?>
    <div class="form-group">
        <label for="pickup_airport"><?php _e('Pick Up', 'taxi-rent'); ?>*</label>
        <select name="pickup_airport" class="form-control" id="pickup_airport_select">
            <option value=""><?php _e('Please Select Airport', 'taxi-rent'); ?></option>
            <?php 
            if(get_option('portlists')):
                $portlists = json_decode(get_option('portlists'));
                foreach($portlists as $k => $sport){
                    echo '<option value="'.$sport.'">'.$sport.'</option>';
                }    
            endif; 
            
            ?>
        </select>
        <input type="text" class="w-100 form-control" name="pickup_airport" id="pickup_airport">
    </div>




    <!-- Dropof -->
    <div class="form-group" id="stop_address" style="display:none;">
        <label for="drop_off_port"><?php _e('Stop Address', 'taxi-rent'); ?>*</label>
        <input type="text" class="w-100" disabled name="drop_off" id="drop_off_port">
    </div>

    <div class="adddropoff mt-2 mb-2">
        <span class="addDropOffButton">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </span>
        <span class="text"><?php _e('Add a stop', 'taxi-rent'); ?></span>
    </div>
    <!-- End Dropof -->




    <div class="form-group">
        <label for="destination"><?php _e('Destination', 'taxi-rent'); ?>*</label>
        <!-- <input type="text" class="w-100" name="destination_airport" id="destination_airport"> -->
        <select name="destination_airport" class="form-control" id="destination_airport_select">
            <option value=""><?php _e('Please Select Airport', 'taxi-rent'); ?></option>
            <?php 
            if(get_option('portlists')):
                $portlists = json_decode(get_option('portlists'));
                foreach($portlists as $k => $sport){
                    echo '<option value="'.$sport.'">'.$sport.'</option>';
                }    
            endif; 
            
            ?>
        </select>

        <input type="text" placeholder="<?php _e('ex. Paris Airport/City/Port/Station', 'taxi-rent'); ?>" name="destination_airport" id="destination_airport" class="form-control airport_distination">
    </div>
    <div class="form-group" id="way">
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
