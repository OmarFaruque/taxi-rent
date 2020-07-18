<?php 
/*
* Necessary Functions
*/


if ( ! function_exists( 'is_taxi_rechargeable_cart' ) ) {

    /**
     * Check if cart contains rechargeable product
     * @return boolean
     */
    function is_taxi_rechargeable_cart() {
        $is_taxi_rechargeable_cart = false;
        if ( ! is_null( wc()->cart) && sizeof( wc()->cart->get_cart() ) > 0 && get_taxi_product() ) {
            foreach ( wc()->cart->get_cart() as $key => $cart_item ) {
                if ( $cart_item['product_id'] == get_taxi_product()->get_id() ) {
                    $is_taxi_rechargeable_cart = true;
                    break;
                }
            }
        }
        return apply_filters( 'woo_is_taxi_rechargeable_cart', $is_taxi_rechargeable_cart);
    }

}



/*
/ Get Taxi product
*/
if(!function_exists('get_taxi_product')){
    function get_taxi_product(){
            if ( !wc_get_product( get_option( '_woo_taxi_rent_product' ) ) ) {
                taxiClass::create_product();
            }
            return wc_get_product(apply_filters( 'taxi_product_id', get_option( '_woo_taxi_rent_product' ) ));
    }
}


if ( ! function_exists( 'update_taxi_partial_payment_session' ) ) {
    /**
     * Refresh WooCommerce session for partial payment.
     * @param boolean $set
     */
    function update_taxi_partial_payment_session( $set = false ) {
        if(!is_null(wc()->session)){
            wc()->session->set( 'is_taxi_partial_payment', $set );
        }
    }

}