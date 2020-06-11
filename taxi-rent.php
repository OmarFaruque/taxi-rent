<?php
/**
 * Plugin Name: WP Taxi Rent
 * Plugin URI: http://larasoftbd.net/
 * Description: Taxi rent service. Payment via woocommerce.jony  
 * Version: 1.0.0
 * Author: larasoft
 * Author URI: https://larasoftbd.net
 * Text Domain: taxi-rent
 * Domain Path: /languages
 * Requires at least: 4.0.0
 * Tested up to: 5.0
 *
 * @package     taxirent
 * @category 	Core
 * @author 		LaraSoft
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('taxiDIR', plugin_dir_path( __FILE__ ));
define('taxiURL', plugin_dir_url( __FILE__ ));

define( 'MY_ACF_PATH', taxiDIR . 'int/advanced-custom-fields-pro/' );
define( 'MY_ACF_URL', taxiDIR . 'int/advanced-custom-fields-pro/' );
// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );


require_once(taxiDIR . 'int/class.php');

new taxiClass;