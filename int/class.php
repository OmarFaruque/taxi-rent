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
            add_action('init', array($this, 'includeAllNecessaryFile'));

            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'taxi_rent_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'taxi_rent_frontend_script') );

            //Add Menu Options
            add_action('admin_menu', array($this, 'taxi_rent_admin_menu_function'));

            //Create product and custom post
            add_action( 'init', array($this, 'create_product' ));

            //create custom meta box
            // add_action( 'save_post', array($this, 'vehicle_price_save_meta_box_data') );

            add_action('init', array($this, 'my_acf_add_local_field_groups'));

            // Booking form Shortcode
            add_shortcode( 'taxi-booking', array($this, 'taxiBookingFormShortcodeCallback') );
            add_shortcode( 'taxi-quote', array($this, 'taxiQuoteCallback') );

            // Filter the content
            add_filter('the_content', array($this, 'addShortcodeToTheContent'));

        }

        /*
        * Taxi Quote Page Callback
        */
        public function taxiQuoteCallback(){
            ob_start();
            require_once( $this->plugin_path . 'include/booking-quote.php' );            
            $output = ob_get_clean();
            echo $output;
        }

        /*
        * Filter The content
        */
        public function addShortcodeToTheContent($content){
            global $post;
            if($post->ID == get_option('quote_page')){
                $content = '[taxi-quote]';
            }
            
            return $content;

        }

        /*
        * Taxi Booking Form Shortcode
        */
        public function taxiBookingFormShortcodeCallback(){
            wp_enqueue_style( 'pe-icon', 'https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css', time(), 'all' );
            
            

            ob_start();
            require_once( $this->plugin_path . 'include/booking-form.php' );            
            $output = ob_get_clean();
            echo $output;
        }

        /*
        * Add files
        */
        public function includeAllNecessaryFile(){
            
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
        function taxi_rent_backend_script($hook){

            // echo 'hook name: ' . $hook . '<br/>';
            $enque = false;
            if($hook == 'vichle_page_taxi-settings') $enque = true;
            if($enque){
                wp_enqueue_style( 'bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', array(), true, 'all' );
                wp_enqueue_style( 'bootstrap-css-toggle', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css', array(), true, 'all' );
                
                wp_enqueue_style( 'TaxiRentCSS', $this->plugin_url . 'asset/css/taxi_rent_backend.css', array(), true, 'all' );

                wp_enqueue_script( 'bootstrap-js-toggle', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js', array(), true );
                wp_enqueue_script( 'TaxiRentJs', $this->plugin_url . 'asset/js/taxi_rent_backend.js', array(), true );
            }
        }

        /*
        * its append add action line 38
        * Appointment frontend Script
        * And we send All note value in javascript from ajax.
        */
        function taxi_rent_frontend_script(){
            wp_enqueue_script( 'jquery-ui-tabs' );
            
            wp_enqueue_style( 'jquery-ui-css', 'http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css', time(), 'all' );
            wp_enqueue_style( 'TaxiRentCSS', $this->plugin_url . 'asset/css/taxi_rent_frontend.css', array(), true, 'all' );
            wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array('jquery'), time(), true  );
            wp_enqueue_script('TaxiRentJS', $this->plugin_url . 'asset/js/taxi_rent_frontend.js', array('jquery'), time(), true);
        }

        /*
        * its append add action line 41
        * Admin Menu
        */
        function taxi_rent_admin_menu_function(){
            add_submenu_page( 'edit.php?post_type=vichle', 'Taxi Settings', 'Settings', 'manage_options', 'taxi-settings', array($this, 'taxisettingspageCallback'), 'dashicons-list-view', 50 );
        }


        /**
         * create rechargeable product
         */
        function create_product() {

            if ( !wc_get_product( get_option( '_woo_taxi_rent_product' ) ) ) {
                $product_args = array(
                    'post_title' => wc_clean( 'Taxi Rent' ),
                    'post_status' => 'private',
                    'post_type' => 'product',
                    'post_excerpt' => '',
                    'post_content' => stripslashes(html_entity_decode( 'Auto generated product for Taxi Rent. please do not delete or update.', ENT_QUOTES, 'UTF-8' ) ),
                    'post_author' => 1
                );
                $product_id = wp_insert_post( $product_args );
                if ( ! is_wp_error( $product_id ) ) {
                    $product = wc_get_product( $product_id );
                    wp_set_object_terms( $product_id, 'simple', 'product_type' );
                    update_post_meta( $product_id, '_stock_status', 'instock' );
                    update_post_meta( $product_id, 'total_sales', '0' );
                    update_post_meta( $product_id, '_downloadable', 'no' );
                    update_post_meta( $product_id, '_virtual', 'yes' );
                    update_post_meta( $product_id, '_regular_price', '' );
                    update_post_meta( $product_id, '_sale_price', '' );
                    update_post_meta( $product_id, '_purchase_note', '' );
                    update_post_meta( $product_id, '_featured', 'no' );
                    update_post_meta( $product_id, '_weight', '' );
                    update_post_meta( $product_id, '_length', '' );
                    update_post_meta( $product_id, '_width', '' );
                    update_post_meta( $product_id, '_height', '' );
                    update_post_meta( $product_id, '_sku', '' );
                    update_post_meta( $product_id, '_product_attributes', array() );
                    update_post_meta( $product_id, '_sale_price_dates_from', '' );
                    update_post_meta( $product_id, '_sale_price_dates_to', '' );
                    update_post_meta( $product_id, '_price', '' );
                    update_post_meta( $product_id, '_sold_individually', 'yes' );
                    update_post_meta( $product_id, '_manage_stock', 'no' );
                    update_post_meta( $product_id, '_backorders', 'no' );
                    update_post_meta( $product_id, '_stock', '' );

                    update_option( '_woo_taxi_rent_product', $product_id );
                }
            }



            // Careate Vachile post type
            $this->makeVichlePostType();
            $this->regtisterVicheleTaxonomy();
        }


        /*
        * Register Custom taxonomy for Vichle
        * Reference: https://codex.wordpress.org/Function_Reference/register_taxonomy
        */
        private function regtisterVicheleTaxonomy(){
            // Add new taxonomy, make it hierarchical (like categories)
            $labels = array(
                'name'              => _x( 'Vichle Type', 'taxonomy general name', 'taxi-rent' ),
                'singular_name'     => _x( 'Vichle Type', 'taxonomy singular name', 'taxi-rent' ),
                'search_items'      => __( 'Search Vichle Type', 'taxi-rent' ),
                'all_items'         => __( 'All Vichle Type\'s', 'taxi-rent' ),
                'parent_item'       => __( 'Parent Vichle Type', 'taxi-rent' ),
                'parent_item_colon' => __( 'Parent Vichle Type:', 'taxi-rent' ),
                'edit_item'         => __( 'Edit Vichle Type', 'taxi-rent' ),
                'update_item'       => __( 'Update Vichle Type', 'taxi-rent' ),
                'add_new_item'      => __( 'Add New Vichle Type', 'taxi-rent' ),
                'new_item_name'     => __( 'New Vichle Type Name', 'taxi-rent' ),
                'menu_name'         => __( 'Vichle Type', 'taxi-rent' ),
            );

            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'vichle-type' ),
            );

            register_taxonomy( 'vichle-type', array( 'vichle' ), $args );
        }

        /*
        * Register Vichel type as custom post type
        */
        private function makeVichlePostType(){
            $labels = array(
                'name'               => __( 'Vichle' ),
                'singular_name'      => __( 'Vichle' ),
                'add_new'            => __( 'Add New Vichle' ),
                'add_new_item'       => __( 'Add New Vichle' ),
                'edit_item'          => __( 'Edit Vichle' ),
                'new_item'           => __( 'Add New Vichle' ),
                'view_item'          => __( 'View Vichle' ),
                'search_items'       => __( 'Search Vichle' ),
                'not_found'          => __( 'No vichle found' ),
                'not_found_in_trash' => __( 'No vichle found in trash' )
            );
        
            $supports = array(
                'title',
                'thumbnail',
            );
        
            $args = array(
                'labels'               => $labels,
                'supports'             => $supports,
                'public'               => true,
                'capability_type'      => 'post',
                'rewrite'              => array( 'slug' => 'vichle' ),
                'has_archive'          => true,
                'menu_position'        => 30,
                'menu_icon'            => $this->plugin_url . 'asset/img/car.png',
            );
        
            register_post_type( 'vichle', $args );
        }

        function my_acf_add_local_field_groups() {
            // echo 'jony_acf';
	
            if( function_exists('acf_add_local_field_group') ):

                acf_add_local_field_group(array (
                    'key' => 'vehicle_details',
                    'title' => 'Vichle details',
                    'fields' => array (
                        array (
                            'key' => 'vehicle_details_field_1',
                            'label' => 'Passenger Capacity',
                            'name' => 'number_of_passengers',
                            'type' => 'number',
                        ),
                        array(
                            'key' => 'vehicle_details_field_2',
                            'label' => 'Luggage Capacity',
                            'name' => 'number_of_luggage',
                            'type' => 'number',
                        ),
                        array(
                            'key' => 'vehicle_details_field_3',
                            'label' => 'First Mile Charge',
                            'name' => 'first_mile_price',
                            'type' => 'number',
                        ),
                        array(
                            'key' => 'vehicle_details_field_4',
                            'label' => 'Price (Per KM)',
                            'name' => 'price',
                            'type' => 'number',
                        ),
                        array(
                            'key' => 'vehicle_details_field_5',
                            'label' => 'Hourly Price (Per Hour)',
                            'name' => 'hr_price',
                            'type' => 'number',
                        ),
                        array (	
                            /* ... Insert generic settings here ... */
                            
                            /* (int) Specify the minimum attachments required to be selected. Defaults to 0 */
                            'min' => 0,
                            
                            /* (int) Specify the maximum attachments allowed to be selected. Defaults to 0 */
                            'max' => 0,
                            
                            /* (string) Specify the image size shown when editing. Defaults to 'thumbnail'. */
                            'preview_size' => 'thumbnail',
                            
                            /* (string) Restrict the image library. Defaults to 'all'.
                            Choices of 'all' (All Images) or 'uploadedTo' (Uploaded to post) */
                            'library' => 'all',
                            
                            /* (int) Specify the minimum width in px required when uploading. Defaults to 0 */
                            'min_width' => 0,
                            
                            /* (int) Specify the minimum height in px required when uploading. Defaults to 0 */
                            'min_height' => 0,
                            
                            /* (int) Specify the minimum filesize in MB required when uploading. Defaults to 0 
                            The unit may also be included. eg. '256KB' */
                            'min_size' => 0,
                            
                            /* (int) Specify the maximum width in px allowed when uploading. Defaults to 0 */
                            'max_width' => 0,
                            
                            /* (int) Specify the maximum height in px allowed when uploading. Defaults to 0 */
                            'max_height' => 0,
                            
                            /* (int) Specify the maximum filesize in MB in px allowed when uploading. Defaults to 0
                            The unit may also be included. eg. '256KB' */
                            'max_size' => 0,
                            
                            /* (string) Comma separated list of file type extensions allowed when uploading. Defaults to '' */
                            'mime_types' => '',
                        )
                    ),
                    'location' => array (
                        array (
                            array (
                                'param' => 'post_type',
                                'operator' => '==',
                                'value' => 'vichle',
                            ),
                        ),
                    ),
                    'menu_order' => 0,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                ));
                
                endif;
        }

        


        function taxisettingspageCallback(){
            $this->processSettingsPageRequest();
            require_once( $this->plugin_path . 'include/settings-page.php' );
        }

        public function vichle_price($postid){
                                $distance = $_REQUEST['distance'] ? $_REQUEST['distance'] : 0;
                                $firstMilePrice = 0;
                                $price = 0;
                                if($distance > 1000){
                                    $price = get_field('first_mile_price', $postid);
                                    $distance = $distance - 1000;
                                }

                                if(get_field('price', $postid)){
                                  $price_per_mitr = get_field('price', $postid) / 1000;
                                  $price = ($price_per_mitr * $distance) + $price;
                                }

                                // Add Vat 
                                $taxi_vat = get_option('taxi_vat', 0);
                                $price += $price * ($taxi_vat / 100);
                                $price = number_format($price, 2);
                                return apply_filters( 'the_vichle_price', $price );
        }

        protected function processSettingsPageRequest(){

            
            if(isset($_REQUEST['taxi_settings_button'])){
                $local_service      = (isset($_REQUEST['local_service']) && $_REQUEST['local_service'] == 'on') ? 1:0;
                $airport_seaport    = (isset($_REQUEST['airport_seaport']) && $_REQUEST['airport_seaport'] == 'on') ? 1:0;
                $hourly_rent        = (isset($_REQUEST['hourly_rent']) && $_REQUEST['hourly_rent'] == 'on') ? 1:0;
                $taxi_vat           = isset($_REQUEST['taxi_vat']) ? $_REQUEST['taxi_vat']:0;
                $quote_page         = $_REQUEST['quote_page'];

                
                update_option( 'local_service', $local_service );
                update_option( 'airport_seaport', $airport_seaport );
                update_option( 'hourly_rent', $hourly_rent );
                update_option( 'quote_page', $quote_page);
                update_option( 'taxi_vat', $taxi_vat);
                
            }

        }



    }
} //if(!class_exists('taxiClass')){ d f
