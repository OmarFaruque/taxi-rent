<?php 
/*
* Payu Form
*/
// global $post;
// $current_user = wp_get_current_user();
// $transationid = get_user_meta( get_current_user_id(), 'tid', true );

// $sctipy_url = 'https://checkout-static.citruspay.com/bolt/run/bolt.min.js';
// if(woo_wallet()->settings_api->get_option('payu_mode', '_wallet_settings_general') == 'on') $sctipy_url = 'https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js'; 
?>
<!-- BOLT Sandbox/test //-->
<!-- <script id="bolt" src="<?php // echo $sctipy_url; ?>" bolt-
color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script> -->

<form action="" method="post"  id="payu_payment_form">
    <input type="submit" name="payusubmit" value="<?php _e('Confirm Payment using PayU', 'trawallet'); ?>">
</form>

     