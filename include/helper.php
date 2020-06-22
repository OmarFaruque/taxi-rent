<?php 
/*
* Helper function 
*/
if ( ! function_exists( 'get_texi_rent_rechargeable_product' ) ) {

    /**
     * get rechargeable product
     * @return WC_Product object
     */
    function get_texi_rent_rechargeable_product() {
        Woo_Wallet_Install::cteate_product_if_not_exist();
        return wc_get_product(apply_filters( 'woo_taxi_rent_rechargeable_product_id', get_option( '_woo_taxi_rent_recharge_product' ) ) );
    }

}