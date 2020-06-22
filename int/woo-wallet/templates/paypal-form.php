<?php  
/*
* Paypal Form
*/
global $post;
$paypalSettings = array();
if(class_exists('WC_Gateway_Paypal')){
    $paypalSettings = get_option( 'woocommerce_paypal_settings' );
}

?>

<form id="paypal_form" action="https://www.sandbox.paypal.com/cgi-bin/webscr"
            method="post" target="_top">
            <input type='hidden' name='business' value='<?php echo (isset($paypalSettings['email'])) ? $paypalSettings['email'] : ''; ?>'> 
            <input type='hidden' name='item_name' value='<?php _e('Wellet Topup', 'wp_trawallet'); ?>'> 
            <input type='hidden' name='item_number' value='Wallet#01'> 
            <input type='hidden' name='amount' value=''> 
            <input type='hidden' name='no_shipping' value='1'> 
            <input type='hidden' name='currency_code' value='USD'> 
            <input type='hidden' name='notify_url' value='<?php echo get_the_permalink( $post->ID ); ?>'>
            <input type='hidden' name='cancel_return' value='<?php echo get_the_permalink( $post->ID ); ?>'>
            <input type='hidden' name='return' value='<?php echo get_the_permalink( $post->ID ); ?>?status=success'>
            <input type="hidden" name="cmd" value="_xclick"> 
            <input type="submit" name="pay_now" id="pay_now" Value="Pay Now">
            
        </form>