<?php 

/*
* Local Service Form
*/
$quote_url = get_the_permalink( get_option('quote_page') );

?>

<form action="<?php echo $quote_url; ?>" method="post">
    <?php wp_nonce_field( 1, 'taxi_booking_nonce' ); ?>
    <div class="form-group">
        <label for="pickup"><?php _e('Pick Up', 'taxi-rent'); ?>*</label>
        <input type="text" class="w-100" name="pickup" id="pickup">
    </div>
    <div class="form-group">
        <label for="destination"><?php _e('Destination', 'taxi-rent'); ?>*</label>
        <input type="text" class="w-100" name="destination" id="destination">
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
<input type="submit" class="btn btn-primary" name="submit_for_quote" value="<?php _e('Show price & book online', 'taxi-rent'); ?>">

</form>