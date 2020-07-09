<?php 

/*
* Local Service Form
*/
$quote_url = get_the_permalink( get_option('quote_page') );

?>

<form action="<?php echo $quote_url; ?>" method="post">
    <?php wp_nonce_field( 1, 'taxi_booking_nonce' ); ?>
    <div class="form-group mb-1">
        <label for="pickup"><?php _e('Pick Up', 'taxi-rent'); ?>*</label>
        <input type="text" class="w-100" name="pickup" id="pickup">
    </div>



    <div class="form-group">
        <label for="destination"><?php _e('Destination', 'taxi-rent'); ?>*</label>
        <input type="text" class="w-100" name="destination" id="destination">
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

    
    <!-- Dropof -->


    <div class="form-group" id="stop_address" style="display:none;">
            
    </div>

    <div class="adddropoff mt-2 mb-2">
        <span class="addDropOffButton local">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </span>
        <span class="text"><?php _e('VIA', 'taxi-rent'); ?></span>
    </div>
    <!-- End Dropof -->

<br>
<input type="hidden" id="distance" name="distance" value="">
<input type="submit" class="btn btn-primary" name="submit_for_quote" value="<?php echo get_option( 'tr_from_button_text', __('Show price & book online', 'taxi-rent') ); ?>">

</form>