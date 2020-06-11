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
            // add_action( 'add_meta_boxes', array($this, 'vehicle_add_meta_box') );
            // add_action( 'save_post', array($this, 'vehicle_price_save_meta_box_data') );

            add_action('init', array($this, 'my_acf_add_local_field_groups'));
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

            // Our custom post type
            $labels = array(
                'name'               => __( 'Vehicle' ),
                'singular_name'      => __( 'Vehicle' ),
                'add_new'            => __( 'Add New Vehicle' ),
                'add_new_item'       => __( 'Add New Vehicle' ),
                'edit_item'          => __( 'Edit Vehicle' ),
                'new_item'           => __( 'Add New Vehicle' ),
                'view_item'          => __( 'View Vehicle' ),
                'search_items'       => __( 'Search Vehicle' ),
                'not_found'          => __( 'No vehicle found' ),
                'not_found_in_trash' => __( 'No vehicle found in trash' )
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
                'menu_icon'            => 'dashicons-calendar-alt',
            );
        
            register_post_type( 'vehicle', $args );


        }

        function my_acf_add_local_field_groups() {
            echo 'jony_acf';
	
            if( function_exists('acf_add_local_field_group') ):

                acf_add_local_field_group(array (
                    'key' => 'vehicle_details',
                    'title' => 'Vehicle details',
                    'fields' => array (
                        array (
                            'key' => 'vehicle_details_field_1',
                            'label' => 'Vehicle capacity number of passengers',
                            'name' => 'number_of_passengers',
                            'type' => 'number',
                        ),
                        array (
                            'key' => 'vehicle_details_field_2',
                            'label' => 'Vehicle capacity number of Luggage',
                            'name' => 'number_of_luggage',
                            'type' => 'number',
                        ),
                        array (
                            'key' => 'vehicle_details_field_3',
                            'label' => 'Travel distance per km based price',
                            'name' => 'km_based_price',
                            'type' => 'number',
                        ),
                        array (
                            'key' => 'vehicle_details_field_4',
                            'label' => 'Travel distance per hourly based price',
                            'name' => 'hourly_based_price',
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
                
                endif;
        }

        function vehicle_add_meta_box() {
            add_meta_box(
                'vehicleMeta',
                esc_html__( 'Vehicle details', 'larasoftbd_shopping_cart' ),
                array($this, 'vehiclecallback'),
                'vehicle'
            );
        }

        
        //Vehicle details
        function vehiclecallback($post){
            /*
            * Practie Post type Formate
            */
            wp_nonce_field( 'larasoftbd_shopping_cart_meta_box', 'larasoftbd_shopping_cart_meta_box_nonce' );
            wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
            $prfx_stored_meta = get_post_meta( $post->ID );

            /*
            * Use get_post_meta() to retrieve an existing value
            * from the database and use the value for the form.
            */
            $number_of_passengers 	            = esc_attr(get_post_meta( $post->ID, 'number_of_passengers', true ));
            $number_of_luggage               	= esc_attr(get_post_meta( $post->ID, 'number_of_luggage', true ));
            $km_based_price 	                = esc_attr(get_post_meta( $post->ID, 'km_based_price', true ));
            $hourly_based_price   	            = esc_attr(get_post_meta( $post->ID, 'hourly_based_price', true ));

            
            //Vehicle capacity number of passengers
            echo '<label style="width:100%;" for="number_of_passengers">';
            echo esc_html__( 'Vehicle capacity number of passengers', 'larasoftbd_shopping_cart' );
            echo '</label> ';
            echo '<input style="width:100%" type="text" id="number_of_passengers" name="number_of_passengers" value="' .  $number_of_passengers  . '" />';
            echo '<br/>';
            echo '<br/>';

            //Vehicle capacity number of Luggage
            echo '<label style="width:100%;" for="number_of_luggage">';
            echo esc_html__( 'Vehicle capacity number of Luggage', 'larasoftbd_shopping_cart' );
            echo '</label> ';
            echo '<input style="width:100%" type="text" id="number_of_luggage" name="number_of_luggage" value="' .  $number_of_luggage  . '" />';
            echo '<br/>';
            echo '<br/>';

            //Travel distance per km based price
            echo '<label style="width:100%;" for="km_based_price">';
            echo esc_html__( 'Travel distance per km based price', 'larasoftbd_shopping_cart' );
            echo '</label> ';
            echo '<input style="width:100%" type="text" id="km_based_price" name="km_based_price" value="' .  $km_based_price  . '" />';
            echo '<br/>';
            echo '<br/>';

            //Travel distance per hourly based price
            echo '<label style="width:100%;" for="hourly_based_price">';
            echo esc_html__( 'Travel distance per hourly based price', 'larasoftbd_shopping_cart' );
            echo '</label> ';
            echo '<input style="width:100%" type="text" id="hourly_based_price" name="hourly_based_price" value="' .  $hourly_based_price  . '" />';
            echo '<br/>';
            echo '<br/>';
        }

        /**
         * When the post is saved, saves our custom data.
         *
         * @param int $post_id The ID of the post being saved.
         */
        function vehicle_price_save_meta_box_data( $post_id ) {

            // Sanitize user input.
            $my_data_text_number_of_passengers 	    = sanitize_text_field( $_POST['number_of_passengers'] );
            $my_data_text_number_of_luggage 	    = sanitize_text_field( $_POST['number_of_luggage'] );
            $my_data_text_km_based_price 	        = sanitize_text_field( $_POST['km_based_price'] );
            $my_data_text_hourly_based_price 	    = sanitize_text_field( $_POST['hourly_based_price'] );

            // Update the meta field in the database.
            update_post_meta( $post_id, 'number_of_passengers', $my_data_text_number_of_passengers );
            update_post_meta( $post_id, 'number_of_luggage', $my_data_text_number_of_luggage );
            update_post_meta( $post_id, 'km_based_price', $my_data_text_km_based_price );
            update_post_meta( $post_id, 'hourly_based_price', $my_data_text_hourly_based_price );
        }


        function submenufunction(){


            echo 'this is me!';

            echo '<pre>';
            print_r( get_option( '_woo_taxi_rent_product' ) );
            echo '</pre>';
        }



    }
} //if(!class_exists('taxiClass')){ d f
