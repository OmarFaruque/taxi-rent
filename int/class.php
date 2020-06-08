<?php 
/*
* Taxi Rent Master class
*/

if(!class_exists('taxiClass')){
    class taxiClass{
        protected $plugin_path;
        protected $plugin_url;

        public function __construct() {
            $plugin_path = taxiDIR;
            $plugin_url = taxiURL;
            $this->includes();
            $this->init();
        }

        protected function init(){
            add_action('wp', array($this, 'includeAllNecessaryFile'));
        }

        /*
        * Add files
        */
        protected function includeAllNecessaryFile(){
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
    }
} //if(!class_exists('taxiClass')){ d
