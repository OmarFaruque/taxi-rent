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
            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'taxi_rent_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'taxi_rent_frontend_script') );

            //Add Menu Options
            add_action('admin_menu', array($this, 'taxi_rent_admin_menu_function'));


            //create custom meta box
            add_action( 'init', array($this, 'createNecesaryPostType'));
            add_action('init', array($this, 'my_acf_add_local_field_groups'));
            add_action('wp_head', array($this, 'paymentFormSubmit'));

            // Booking form Shortcode
            add_shortcode( 'taxi-booking', array($this, 'taxiBookingFormShortcodeCallback') );
            add_shortcode( 'taxi-quote', array($this, 'taxiQuoteCallback') );

            // Filter the content
            add_filter('the_content', array($this, 'addShortcodeToTheContent'));

            add_action('woocommerce_before_calculate_totals', array($this, 'woo_taxi_set_product_price'));
            add_filter('woocommerce_add_to_cart_validation', array($this, 'restrict_other_from_add_to_cart'), 20);
            add_filter('woocommerce_is_purchasable', array($this, 'make_taxi_rent_product_purchasable'), 10, 2);

            
            // Ajax Actions
            add_action( 'wp_ajax_addNewAirportListByAjax', array($this, 'addNewAirportListByAjax') );
            // Delete Airport List bya Ajax 
            add_action( 'wp_ajax_deleteAirportListItemByAjax', array($this, 'deleteAirportListItemByAjax') );

            add_action( 'woocommerce_add_order_item_meta', array($this, 'add_order_item_meta') , 10, 3 );

            add_action('wp_head', array($this, 'addAdditionalMetaTags'));

            // add_action( 'template_redirect', 'action_woocommerce_cart_redirect_first_Time', 10, 1 ); 
        }



        /*
        * Avoid First time redirection
        */

        /*
        * Test function 
        */
        public function addAdditionalMetaTags(){

            echo 'Request<pre>';
            print_r($_REQUEST);
            echo '</pre>';

            echo 'Cookies<pre>';
            print_r($_COOKIE);
            echo '</pre>';

            echo '<meta content="width=device-width, initial-scale=1" name="viewport" />';
            
        }


        /*
        * Add Order Mta
        */
        public function add_order_item_meta($item_id, $cart_item, $cart_item_key){
            if(isset( $cart_item['taxi-meta'])){
                $vehicle_id = $cart_item['taxi-meta']['vehicle_id'];
                
                // wc_add_order_item_meta( $item_id, 'cover_image_id', $cart_item['cover_id'] ); 
               foreach($cart_item['taxi-meta'] as $k=> $single_value){
                
                switch($k){
                    case 'vehicle_id':
                        $k = __('Vehicle', 'taxi-rent');
                        $single_value = sprintf('<a target="_blank" href="%s">%s</a>', get_edit_post_link($single_value), get_the_title( $single_value ) );
                    break;
                    default:
                        $k = str_replace('_', ' ', $k);
                        $k = ucwords($k);
                }
                wc_add_order_item_meta( $item_id, $k, $single_value );
               }
            }
        }


        /*
        * Delete airport List 
        */
        public function deleteAirportListItemByAjax(){

            $airportList = get_option( 'portlists');
            $airportList = $airportList ? json_decode($airportList) : array();
            $airportList = (array)$airportList;

            $success = false;
            
                
            unset($airportList[$_POST['place_id']]);
            $airportList = array_values( $airportList );
            $airportList = json_encode( $airportList );
            update_option( 'portlists', $airportList );
            $success = true;
            

            $msg = $success ? 'success' : 'fail';
            wp_send_json( array(
                'msg' => $msg
            ) );
            wp_die();
        }



        /*
        * Ajax Action for add airport list
        */
        public function addNewAirportListByAjax(){
            // delete_option( 'portlists' );
            $airportList = get_option( 'portlists');
            $airportList = $airportList ? json_decode($airportList) : array();
            $airportList = (array)$airportList;
            
            
            $success = true;
            
            $newItem = array(
                    'port_a' => $_POST['port_a'],
                    'port_b' => $_POST['port_b'],
                    'price' => $_POST['price']
            );
            array_push($airportList, $newItem);

            $airportList = array_values( $airportList );
               
            
            $airportList = json_encode($airportList);
            update_option('portlists', $airportList);
            
            $msg = $success ? 'success' : 'fail';
            wp_send_json( array(
                'msg' => $msg
            ) );
            wp_die();
        }

        /**
         * Make rechargeable product purchasable
         * @param boolean $is_purchasable
         * @param WC_Product object $product
         * @return boolean
         */
        public function make_taxi_rent_product_purchasable($is_purchasable, $product) {
            $wallet_product = get_taxi_product();
            if ($wallet_product) {
                if ($wallet_product->get_id() == $product->get_id()) {
                    $is_purchasable = true;
                }
            }
            return $is_purchasable;
        }


        /**
         * Set topup product price at run time
         * @param OBJECT $cart
         * @return NULL
         */
        public function woo_taxi_set_product_price($cart) {
            $product = get_taxi_product();
            if (!$product && empty($cart->cart_contents)) {
                return;
            }
            foreach ($cart->cart_contents as $key => $value) {
                if (isset($value['taxi_rent_amount']) && $value['taxi_rent_amount'] && $product->get_id() == $value['product_id']) {
                    $value['data']->set_price($value['taxi_rent_amount']);
                }
            }
        }


        /**
         * Restrict customer to order other product along with rechargeable product
         * @param boolean $valid
         * @return boolean
         */
        public function restrict_other_from_add_to_cart($valid) {
            if (is_taxi_rechargeable_cart()) {
                wc_add_notice(apply_filters('woo_taxi_restrict_other_from_add_to_cart', __('You can not add another product while your cart contains with taxi product.', 'taxi-rent')), 'error');
                $valid = false;
            }
            return $valid;
        }



        /*
        * Woocommerce payment process
        */
        public function woocommercer_payment_process(){
            $product = get_taxi_product();
            global $woocommerce;
            if ($product) {
                add_filter('woocommerce_add_cart_item_data', array($this, 'add_taxi_product_price_to_cart_item_data'), 10, 2);
                $woocommerce->cart->empty_cart();
                $woocommerce->session->set( 'cart', array() );
                $metas = array();
                $posts = $_POST;              
               
                unset($posts['woocommerce-edit-address-nonce']);
                unset($posts['save-account-details-nonce']);
                unset($posts['woocommerce-reset-password-nonce']);
                unset($posts['_wpnonce']);
                unset($posts['woocommerce-login-nonce']);
                unset($posts['submit_type']);
                unset($posts['taxi_rent_amount']);
                $metas['taxi-meta'] = $posts;
                
                $woocommerce->cart->add_to_cart($product->get_id(), 1, '0', array(), $metas);

                wc_setcookie( 'woocommerce_items_in_cart', 1 );
                wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );
                do_action( 'woocommerce_set_cart_cookies', true );

                $redirect_url = apply_filters('woo_taxi_redirect_to_checkout_after_added_amount', true) ? wc_get_checkout_url() : wc_get_cart_url();
                // echo 'redirect url: ' . $redirect_url . '<br/>';
                echo '<script>window.location.replace("'.$redirect_url.'");</script>';
            }
        }




        /**
         * WooCommerce add cart item data
         * @param array $cart_item_data
         * @param int $product_id
         * @return array
         */
        public function add_taxi_product_price_to_cart_item_data($cart_item_data, $product_id) {
            $product = wc_get_product($product_id);
            if (isset($_POST['taxi_rent_amount']) && $product) {
                $taxi_rent_amount = apply_filters('woo_wallet_rechargeable_amount', $_POST['taxi_rent_amount']);
                $taxi_rent_amount = $taxi_rent_amount;
                $cart_item_data['taxi_rent_amount'] = $taxi_rent_amount;
            }
            return $cart_item_data;
        }

        /*
        */
        public function paymentFormSubmit(){
            if(isset($_REQUEST['submit_type']) && $_REQUEST['submit_type'] == 'pay_now'){
                $this->woocommercer_payment_process();
            }
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
            $enque = false;
            if($hook == 'vehicle_page_taxi-settings') $enque = true;
            if($enque){
                wp_enqueue_style( 'bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', array(), true, 'all' );
                wp_enqueue_style( 'bootstrap-css-toggle', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css', array(), true, 'all' );
                
                wp_enqueue_style( 'TaxiRentCSS', $this->plugin_url . 'asset/css/taxi_rent_backend.css', array(), true, 'all' );

                wp_enqueue_script( 'bootstrap-js-toggle', 'https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js', array(), true );
                wp_enqueue_script( 'TaxiRentJs', $this->plugin_url . 'asset/js/taxi_rent_backend.js', array(), true );
                wp_localize_script( 'TaxiRentJs', 'admin_ajax',
                    array( 
                        'ajaxurl' => admin_url( 'admin-ajax.php' )
                    )
                );
            }
        }

        /*
        * its append add action line 38
        * Appointment frontend Script
        * And we send All note value in javascript from ajax.
        */
        function taxi_rent_frontend_script(){
            wp_enqueue_script( 'jquery-ui-tabs' );
            
            wp_enqueue_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css', time(), 'all' );
            wp_enqueue_style( 'TaxiRentCSS', $this->plugin_url . 'asset/css/taxi_rent_frontend.css', array(), true, 'all' );
            
            
            wp_enqueue_script( 'jquery-ui-cdn', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array('jquery'), time(), true  );
            wp_enqueue_script( 'blockui', $this->plugin_url . 'asset/js/jquery.blockUI.js', array('jquery'), time(), true ); 
            wp_enqueue_script( 'jquery-form-validate', 'https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js', array('jquery'), time(), true ); 
                
            // wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', array('jquery'), true );
            

            wp_enqueue_script('TaxiRentJSs', $this->plugin_url . 'asset/js/taxi_rent_frontend.js', array('jquery'), time(), true);
        }

        /*
        * its append add action line 41
        * Admin Menu
        */
        function taxi_rent_admin_menu_function(){
            add_submenu_page( 'edit.php?post_type=vehicle', 'Taxi Settings', 'Settings', 'manage_options', 'taxi-settings', array($this, 'taxisettingspageCallback'), 'dashicons-list-view', 50 );
        }


       



        /*
        * Create Necessary Post Type
        */
        public function createNecesaryPostType(){
            // Careate Vachile post type
            $this->makeVehiclePostType();
            $this->regtisterVicheleTaxonomy();
        }



        /**
         * create rechargeable product
         */
        function create_product() {
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


        /*
        * Register Custom taxonomy for Vehicle
        * Reference: https://codex.wordpress.org/Function_Reference/register_taxonomy
        */
        private function regtisterVicheleTaxonomy(){
            // Add new taxonomy, make it hierarchical (like categories)
            $labels = array(
                'name'              => _x( 'Vehicle Type', 'taxonomy general name', 'taxi-rent' ),
                'singular_name'     => _x( 'Vehicle Type', 'taxonomy singular name', 'taxi-rent' ),
                'search_items'      => __( 'Search Vehicle Type', 'taxi-rent' ),
                'all_items'         => __( 'All Vehicle Type\'s', 'taxi-rent' ),
                'parent_item'       => __( 'Parent Vehicle Type', 'taxi-rent' ),
                'parent_item_colon' => __( 'Parent Vehicle Type:', 'taxi-rent' ),
                'edit_item'         => __( 'Edit Vehicle Type', 'taxi-rent' ),
                'update_item'       => __( 'Update Vehicle Type', 'taxi-rent' ),
                'add_new_item'      => __( 'Add New Vehicle Type', 'taxi-rent' ),
                'new_item_name'     => __( 'New Vehicle Type Name', 'taxi-rent' ),
                'menu_name'         => __( 'Vehicle Type', 'taxi-rent' ),
            );

            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'Vehicle-type' ),
            );

            register_taxonomy( 'Vehicle-type', array( 'Vehicle' ), $args );
        }

        /*
        * Register Vichel type as custom post type
        */
        private function makeVehiclePostType(){
            $labels = array(
                'name'               => __( 'Vehicle' ),
                'singular_name'      => __( 'Vehicle' ),
                'add_new'            => __( 'Add New Vehicle' ),
                'add_new_item'       => __( 'Add New Vehicle' ),
                'edit_item'          => __( 'Edit Vehicle' ),
                'new_item'           => __( 'Add New Vehicle' ),
                'view_item'          => __( 'View Vehicle' ),
                'search_items'       => __( 'Search Vehicle' ),
                'not_found'          => __( 'No Vehicle found' ),
                'not_found_in_trash' => __( 'No Vehicle found in trash' )
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
                'rewrite'              => array( 'slug' => 'vehicle' ),
                'has_archive'          => true,
                'menu_position'        => 30,
                'menu_icon'            => $this->plugin_url . 'asset/img/car.png',
            );
        
            register_post_type( 'vehicle', $args );
        }

        function my_acf_add_local_field_groups() {
            // echo 'jony_acf';
	
            if( function_exists('acf_add_local_field_group') ):

                acf_add_local_field_group(array (
                    'key' => 'vehicle_details',
                    'title' => 'Vehicle details',
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
                            'label' => 'Price (Per Mile)',
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
                                'value' => 'vehicle',
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




                acf_add_local_field_group(array (
                    'key' => 'vehicle_details_extra',
                    'title' => 'Extra Service',
                    'fields' => array (
                        array(
                            'key' => 'baby_set_over_5',
                            'label' => __('Baby Set Price (Over 5 years)', 'taxi-rent'),
                            'name' => 'baby_over_5',
                            'type' => 'number',
                        ),
                        array(
                            'key' => 'baby_set_over_5_img',
                            'label' => __('Baby Set Image (Over 5 years)', 'taxi-rent'),
                            'name' => 'baby_over_5_img',
                            'type' => 'image',
                        ),
                        
                        array(
                            'key' => 'baby_set_under_5',
                            'label' => __('Baby Set Price (Under 5 years)', 'taxi-rent'),
                            'name' => 'baby_under_5',
                            'type' => 'number',
                        ),

                        array(
                            'key' => 'baby_set_under_5_img',
                            'label' => __('Baby Set Image (Under 5 years)', 'taxi-rent'),
                            'name' => 'baby_under_5_img',
                            'type' => 'image',
                        ),

                        array(
                            'key' => 'meet_n_greet',
                            'label' => __('Meet & Greet', 'taxi-rent'),
                            'name' => 'meet_n_greet',
                            'type' => 'number',
                        ),

                        array(
                            'key' => 'meet_n_greet_img',
                            'label' => __('Meet & Greet Image', 'taxi-rent'),
                            'name' => 'meet_n_greet_img',
                            'type' => 'image',
                        ),


                        array(
                            'key' => 'car_park',
                            'label' => __('Car Park', 'taxi-rent'),
                            'name' => 'car_park',
                            'type' => 'number',
                        ),

                        array(
                            'key' => 'car_park_img',
                            'label' => __('Car Park Image', 'taxi-rent'),
                            'name' => 'car_park_img',
                            'type' => 'image',
                        ),
                    ),
                    'location' => array (
                        array (
                            array (
                                'param' => 'post_type',
                                'operator' => '==',
                                'value' => 'vehicle',
                            ),
                        ),
                    ),
                    'menu_order' => 1,
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

        public function vehicle_price($postid){

            // echo '<pre>';
            // print_r($_REQUEST);
            // echo '</pre>';
            $distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : 0;
            $firstMilePrice = 0;
            $price = 0;

            if($distance > 1609.34){
                $price = get_field('first_mile_price', $postid);
                $distance = $distance - 1609.34;
            }
            if(get_field('price', $postid)){
                $miles = $distance / 1609.34;
                $priceOther = get_field('price', $postid) * $miles;
                $price = $priceOther + $price;
            }

            // Add Way 
            if(isset($_REQUEST['way'])) $price = $price * $_REQUEST['way'];

            // Calculate Hourly Price
            if(isset($_REQUEST['submit_hourly'])) $price = get_field('hr_price', $postid) * $_REQUEST['hours'];

            // fixed Price Calculate for port
            if(isset($_POST['destination_airport']) && !empty($_POST['destination_airport'])){
                $price = 0;
                if(get_option('portlists')):
                    $portlists = json_decode(get_option('portlists'));
                    foreach($portlists as $s){
                        if($s->port_a == $_POST['pickup_airport'] && $s->port_b == $_POST['destination_airport']){
                            $price = $s->price;
                        }
                    }
                endif;
                $price = (int)$price * $_REQUEST['way'];
            } 

            // Add Vat 
            $taxi_vat = get_option('taxi_vat', 0);
            $price += $price * ($taxi_vat / 100);
            $price = number_format($price, 2);
            return apply_filters( 'the_Vehicle_price', $price );
        }

        protected function processSettingsPageRequest(){

            
            if(isset($_REQUEST['taxi_settings_button'])){
                $local_service          = (isset($_REQUEST['local_service']) && $_REQUEST['local_service'] == 'on') ? 1:0;
                $airport_seaport        = (isset($_REQUEST['airport_seaport']) && $_REQUEST['airport_seaport'] == 'on') ? 1:0;
                $hourly_rent            = (isset($_REQUEST['hourly_rent']) && $_REQUEST['hourly_rent'] == 'on') ? 1:0;
                $taxi_vat               = isset($_REQUEST['taxi_vat']) ? $_REQUEST['taxi_vat']:0;
                $map_api                = isset($_REQUEST['map_api']) ? $_REQUEST['map_api']:'AIzaSyDIvHe8zwX9-D5YE39wEAqseTtsRP7EyvQ';
                $tr_from_button_text    = isset($_REQUEST['tr_from_button_text']) ? $_REQUEST['tr_from_button_text']: __('Show price & book online', 'taxi-rent');
                $tr_car_select_btn      = isset($_REQUEST['tr_car_select_btn']) ? $_REQUEST['tr_car_select_btn']: __('Select Car', 'taxi-rent');
                
                $quote_page             = $_REQUEST['quote_page'];

                
                update_option( 'local_service', $local_service );
                update_option( 'airport_seaport', $airport_seaport );
                update_option( 'hourly_rent', $hourly_rent );
                update_option( 'quote_page', $quote_page);
                update_option( 'taxi_vat', $taxi_vat);
                update_option( 'map_api', $map_api);
                update_option( 'tr_from_button_text', $tr_from_button_text);
                update_option( 'tr_car_select_btn', $tr_car_select_btn);
                
            }

        }



    }
} //if(!class_exists('taxiClass')){ d f
