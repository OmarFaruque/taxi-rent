<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



// include dependencies file
if ( ! class_exists( 'Woo_Wallet_Dependencies' ) ){
    include_once dirname( __FILE__) . '/includes/class-woo-wallet-dependencies.php';
}

// Include the main class.
if ( ! class_exists( 'WooWallet' ) ) {
    include_once dirname( __FILE__) . '/includes/class-woo-wallet.php';
}

$wallet_init = new WooWallet;
add_action( 'init', array( $wallet_init, 'init' ), 5);

function woo_wallet(){    
    return WooWallet::instance();
}

$GLOBALS['woo_wallet'] = woo_wallet();

