<?php 
/*
* Taxi Rent Master class
*/

if(!class_exists('taxiClass')){
    class taxiClass{
        public $plugin_path;
        public $plugin_url;

        public function __construct() {
            $this->plugin_path = taxiDIR;
            $this->plugin_url = taxiURL;
            $this->includes();
            $this->init();
        }

        public function init(){
            add_action('wp', array($this, 'includeAllNecessaryFile'));

            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'taxi_rent_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'taxi_rent_frontend_script') );

            //Add Menu Options
            add_action('admin_menu', array($this, 'taxi_rent_admin_menu_function'));
        }

        /*
        * Add files
        */
        public function includeAllNecessaryFile(){
            define( 'MY_ACF_PATH', $this->$plugin_path . '/advanced-custom-fields-pro/includes/acf/' );
            define( 'MY_ACF_URL', $this->$plugin_path . '/advanced-custom-fields-pro/includes/acf/' );

            // Include the ACF plugin.
            include_once( MY_ACF_PATH . 'acf.php' );
        }

        /**
        * load plugin files
        */
        public function includes() {
            require_once($this->plugin_path . 'include/helper.php');
        }

        /*
        * its append add action line 36
        * Appointment backend Script
        */
        function taxi_rent_backend_script(){
            wp_enqueue_style( 'TaxiRentCSS', $this->plugin_url . 'asset/css/taxi_rent_backend.css', array(), true, 'all' );
            wp_enqueue_script( 'TaxiRentJs', $this->plugin_url . 'asset/js/taxi_rent_backend.js', array(), true );
        }

        /*
        * its append add action line 38
        * Appointment frontend Script
        * And we send All note value in javascript from ajax.
        */
        function larasoftbd_Note_frontend_script(){
            wp_enqueue_style( 'TaxiRentCSS', $this->plugin_url . 'asset/css/taxi_rent_frontend.css', array(), true, 'all' );
            wp_enqueue_script('TaxiRentJS', $this->plugin_url . 'asset/js/taxi_rent_frontend.js', array('jquery'), time(), true);
        }

        /*
        * its append add action line 41
        * Admin Menu
        */
        function taxi_rent_admin_menu_function(){
            add_menu_page( 'Taxi Rent', 'Taxi Rent', 'manage_options', 'taxi-rent-menu', array($this, 'submenufunction'), 'dashicons-list-view', 50 );
        }

        function submenufunction(){


            echo 'this is me!';
        }



    }
} //if(!class_exists('taxiClass')){ d f
