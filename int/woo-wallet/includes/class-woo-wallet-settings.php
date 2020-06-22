<?php

/**
 * Woo Wallet settings
 *
 * @author Subrata Mal
 */
if ( ! class_exists( 'Woo_Wallet_Settings' ) ):

    class Woo_Wallet_Settings {
        /* setting api object */

        private $settings_api;

        /**
         * Class constructor
         * @param object $settings_api
         */
        public function __construct( $settings_api) {
            $this->settings_api = $settings_api;
            add_action( 'admin_init', array( $this, 'plugin_settings_page_init' ) );
            add_action( 'admin_menu', array( $this, 'admin_menu' ), 60 );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        }

        /**
         * wc wallet menu
         */
        public function admin_menu() {
            add_submenu_page( 'woo-wallet', __( 'Settings', 'woo-wallet' ), __( 'Settings', 'woo-wallet' ), 'manage_options', 'woo-wallet-settings', array( $this, 'plugin_page' ) );
        }

        /**
         * admin init 
         */
        public function plugin_settings_page_init() {
            
            //set the settings
            $this->settings_api->set_sections( $this->get_settings_sections() );
            foreach ( $this->get_settings_sections() as $section) {
                if (method_exists( $this, "update_option_{$section['id']}_callback" ) ) {
                    add_action( "update_option_{$section['id']}", array( $this, "update_option_{$section['id']}_callback" ), 10, 3);
                }
            }
            $this->settings_api->set_fields( $this->get_settings_fields() );
            //initialize settings
            $this->settings_api->admin_init();
        }

        /**
         * Enqueue scripts and styles
         */
        public function admin_enqueue_scripts() {
            $screen = get_current_screen();
            $screen_id = $screen ? $screen->id : '';
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            wp_register_script( 'woo-wallet-admin-settings', woo_wallet()->plugin_url() . '/inc/woo-wallet/assets/js/admin/admin-settings' . $suffix . '.js', array( 'jquery' ), WOO_WALLET_PLUGIN_VERSION);
            if (in_array( $screen_id, array( 'woowallet_page_woo-wallet-settings', 'terawallet_page_woo-wallet-settings' ) ) ) {
                wp_enqueue_style( 'dashicons' );
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_style( 'woo_wallet_admin_styles' );
                wp_enqueue_media();
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'woo-wallet-admin-settings' );
                $localize_param = array(
                    'screen_id' => $screen_id,
                    'gateways' => $this->get_wc_payment_gateways( 'id' )
                );
                wp_localize_script( 'woo-wallet-admin-settings', 'woo_wallet_admin_settings_param', $localize_param);
            }
        }

        /**
         * Setting sections
         * @return array
         */
        public function get_settings_sections() {
            $sections = array(
                array(
                    'id' => '_wallet_settings_general',
                    'title' => __( 'General', 'woo-wallet' ),
                    'icon' => 'dashicons-admin-generic',
                ),
                array(
                    'id' => '_wallet_settings_credit',
                    'title' => __( 'Shortcode', 'woo-wallet' ),
                    'icon' => 'dashicons-money'
                ),
                // array(
                //     'id' => '_wallet_settings_csv',
                //     'title' => __( 'Import & Export', 'woo-wallet' ),
                //     'icon' => 'dashicons-upload'
                // ),
                // array(
                //     'id' => '_wallet_settings_button',
                //     'title' => __( 'Create Transfer Button', 'woo-wallet' ),
                //     'icon' => 'dashicons-groups'
                // )
            );
            return apply_filters( 'woo_wallet_settings_sections', $sections);
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        public function get_settings_fields() {

            $blogusers = get_users( 'orderby=nicename');
            $usrsArrays = array();
            foreach($blogusers as $su) $usrsArrays[$su->ID] = $su->user_nicename;
            
            $settings_fields = array(
                '_wallet_settings_general' => array_merge( array(
                    array(
                        'name' => 'paypal',
                        'label' => __( 'Rechargea with Paypal', 'woo-wallet' ),
                        'desc' => __( 'If checked user will be able to recharage wallet using paypal.', 'woo-wallet' ),
                        'type' => 'checkbox',
                        'default' => 'on'
                    ),
                    
                    array(
                        'name' => 'payu',
                        'label' => __( 'Rechargea with PayU', 'woo-wallet' ),
                        'desc' => __( 'If checked user will be able to recharage wallet using PayU.', 'woo-wallet' ),
                        'type' => 'checkbox',
                        'default' => 'on'
                    ),

                    // array(
                    //     'name' => 'payu_mode',
                    //     'label' => __( 'PayU Test Mode', 'woo-wallet' ),
                    //     'desc' => __( 'If checked payment should go to test PayU.', 'woo-wallet' ),
                    //     'type' => 'checkbox',
                    //     'default' => 'on'
                    // ),
                    // array(
                    //     'name' => 'payu_merchant_key',
                    //     'label' => __( 'PayU Merchant Key', 'woo-wallet' ),
                    //     'desc' => __( 'PayU Merchant key.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'fn3vQSZ7'
                    // ),
                    // array(
                    //     'name' => 'payu_merchant_salt',
                    //     'label' => __( 'Merchant Salt', 'woo-wallet' ),
                    //     'desc' => __( 'Merchant Salt.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'Zgk0yf7BpO'
                    // ),
                    // array(
                    //     'name' => 'payu_merchant_key_test',
                    //     'label' => __( 'PayU Merchant Key (test)', 'woo-wallet' ),
                    //     'desc' => __( 'PayU Merchant key.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'fn3vQSZ7'
                    // ),
                    // array(
                    //     'name' => 'payu_merchant_salt_test',
                    //     'label' => __( 'Merchant Salt (test)', 'woo-wallet' ),
                    //     'desc' => __( 'Merchant Salt.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'Zgk0yf7BpO'
                    // ),
                    array(
                        'name' => 'stripe',
                        'label' => __( 'Rechargea with Stripe', 'woo-wallet' ),
                        'desc' => __( 'If checked user will be able to recharage wallet using Stripe.', 'woo-wallet' ),
                        'type' => 'checkbox',
                        'default' => 'on'
                    ),
                    // array(
                    //     'name' => 'stripe_mode',
                    //     'label' => __( 'Stripe Test Mode', 'woo-wallet' ),
                    //     'desc' => __( 'If checked payment should go to test stripe.', 'woo-wallet' ),
                    //     'type' => 'checkbox',
                    //     'default' => 'on'
                    // ),
                    // array(
                    //     'name' => 'test_publish_key',
                    //     'label' => __( 'Stripe Publishable Key (test)', 'woo-wallet' ),
                    //     'desc' => __( 'Stripe publish key.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'pk_test_VgIalaSCGif7ENXR4Dup6Ce3'
                    // ),
                    // array(
                    //     'name' => 'test_secret_key',
                    //     'label' => __( 'Stripe Secret Key (test)', 'woo-wallet' ),
                    //     'desc' => __( 'Stripe Secret key.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'sk_test_gQUoQ1StikKhZbRDfChxUL8a'
                    // ),
                    // array(
                    //     'name' => 'live_publish_key',
                    //     'label' => __( 'Stripe Publishable Key (Live)', 'woo-wallet' ),
                    //     'desc' => __( 'Stripe publish key.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'pk_test_VgIalaSCGif7ENXR4Dup6Ce3'
                    // ),
                    // array(
                    //     'name' => 'live_secret_key',
                    //     'label' => __( 'Stripe Secret Key (Live)', 'woo-wallet' ),
                    //     'desc' => __( 'Stripe Secret key.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'sk_test_gQUoQ1StikKhZbRDfChxUL8a'
                    // ),
                    array(
                        'name' => 'coinbase_commerce',
                        'label' => __( 'Rechargea with Coinbase Commerce', 'woo-wallet' ),
                        'desc' => __( 'If checked user will be able to recharage wallet using Coinbase Commerce.', 'woo-wallet' ),
                        'type' => 'checkbox',
                        'default' => 'on'
                    ), 
                    // array(
                    //     'name' => 'coinbase_webhook',
                    //     'label' => __( 'Coinbase Webhook', 'woo-wallet' ),
                    //     'default' => get_home_url( ) . '?webhook=true',
                    //     'desc' => __( 'Set above url for payment complete on your <a target="_blank" href="https://commerce.coinbase.com/dashboard/settings">Coinbase Ecommerce Account</a>.', 'woo-wallet' ),
                    //     'type' => 'labelonly',
                    // ),
                    // array(
                    //     'name' => 'coinbase_hook_secrate_key',
                    //     'label' => __( 'Coinbase Hook Secrate Key', 'woo-wallet' ),
                    //     'desc' => __( 'Coinbase Hook Secret key.', 'woo-wallet' ),
                    //     'type' => 'text',
                    //     'default' => 'eeeca5bc-0249-42a9-b010-b257f2607bfc'
                    // )
                ), $this->get_wc_tax_options(), array(
                    array(
                        'name' => 'min_topup_amount',
                        'label' => __( 'Minimum Topup Amount', 'woo-wallet' ),
                        'desc' => __( 'The minimum amount needed for wallet top up', 'woo-wallet' ),
                        'type' => 'number',
                        'step' => '0.01'
                    ),
                    array(
                        'name' => 'max_topup_amount',
                        'label' => __( 'Maximum Topup Amount', 'woo-wallet' ),
                        'desc' => __( 'The maximum amount needed for wallet top up', 'woo-wallet' ),
                        'type' => 'number',
                        'step' => '0.01'
                    ) ), $this->wp_menu_locations(), array(
                    array(
                        'name' => 'is_enable_wallet_transfer',
                        'label' => __( 'Allow Wallet Transfer', 'woo-wallet' ),
                        'desc' => __( 'If checked user will be able to transfer fund to another user.', 'woo-wallet' ),
                        'type' => 'checkbox',
                        'default' => 'on'
                    ),
                    array(
                        'name' => 'min_transfer_amount',
                        'label' => __( 'Minimum Transfer Amount', 'woo-wallet' ),
                        'desc' => __( 'Enter minimum transfer amount', 'woo-wallet' ),
                        'type' => 'number',
                        'step' => '0.01'
                    ),
                    array(
                        'name' => 'transfer_charge_type',
                        'label' => __( 'Transfer charge type', 'woo-wallet' ),
                        'desc' => __( 'Select transfer charge type percentage or fixed', 'woo-wallet' ),
                        'type' => 'select',
                        'options' => array( 'percent' => __( 'Percentage', 'woo-wallet' ), 'fixed' => __( 'Fixed', 'woo-wallet' ) ),
                        'size' => 'regular-text wc-enhanced-select'
                    ),
                    array(
                        'name' => 'transfer_charge_amount',
                        'label' => __( 'Transfer charge Amount', 'woo-wallet' ),
                        'desc' => __( 'Enter transfer charge amount', 'woo-wallet' ),
                        'type' => 'number',
                        'step' => '0.01'
                    ), 
                    array(
                        'name' => 'currency_symbol',
                        'label' => __( 'Currency Symbol', 'woo-wallet' ),
                        'desc' => __( 'Enter Currency Symbol.', 'woo-wallet' ),
                        'type' => 'text',
                        'default' => '$'
                    ) 
                    
                    )
                ),
                '_wallet_settings_credit' => array_merge( array(
                    array(
                        'name' => 'terawallet-change-sk',
                        'label' => __( 'User SK change button', 'woo-wallet' ),
                        'default' => '[terawallet-change-sk]',
                        'desc' => __( 'User can change own SK using button.', 'woo-wallet' ),
                        'type' => 'labelonly',
                    ),
                    array(
                        'name' => 'tra-wallet',
                        'label' => __( 'Trawallet Wallet Shortcode', 'woo-wallet' ),
                        'default' => '[tra-wallet]',
                        'desc' => __( 'User wallet wrap.', 'woo-wallet' ),
                        'type' => 'labelonly',
                    ),
                    array(
                        'name' => 'tra-mini-wallet',
                        'label' => __( 'Tra Mini Wallet', 'woo-wallet' ),
                        'default' => '[tra-mini-wallet]',
                        'desc' => __( 'User wallet balance wrap.', 'woo-wallet' ),
                        'type' => 'labelonly',
                    ), 
                    array(
                        'name' => 'trawallet-id',
                        'label' => __( 'Trawallet User ID', 'woo-wallet' ),
                        'default' => '[trawallet show=ID]',
                        'desc' => __( 'User ID.', 'woo-wallet' ),
                        'type' => 'labelonly',
                    ),
                    array(
                        'name' => 'trawallet-sk',
                        'label' => __( 'Trawallet User SK', 'woo-wallet' ),
                        'default' => '[trawallet show=SK]',
                        'desc' => __( 'User SK.', 'woo-wallet' ),
                        'type' => 'labelonly',
                    ),
                    array(
                        'name' => 'trawallet-member-no',
                        'label' => __( 'Trawallet User Member No.', 'woo-wallet' ),
                        'default' => '[trawallet show=member-no]',
                        'desc' => __( 'User Member No.', 'woo-wallet' ),
                        'type' => 'labelonly',
                    )
                    ), array()
                ),
                '_wallet_settings_csv' => array_merge( array(
                    array(
                        'name' => 'importexport',
                        'label' => __( 'Download Demo CSV', 'woo-wallet' ),
                        'default' => '',
                        'desc' => __( 'User wallet wrap.', 'woo-wallet' ),
                        'type' => 'importexport',
                    ),
                    array(
                        'name' => 'import',
                        'label' => __( 'Upload data via CSV', 'woo-wallet' ),
                        'default' => '',
                        'desc' => __( 'User wallet wrap.', 'woo-wallet' ),
                        'type' => 'uploadata',
                    ),
                    
                    ), array()
                ),
                '_wallet_settings_button' => array_merge( array(
                    array(
                        'name' => 'transfarable_amount',
                        'label' => __( 'Amount', 'woo-wallet' ),
                        'default' => '',
                        'desc' => __( 'User Transfarable amount.', 'woo-wallet' ),
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'transfer_to',
                        'label' => __( 'Transfer To', 'woo-wallet' ),
                        'default' => get_option('admin_email'),
                        'desc' => __( 'User Transfarable amount.', 'woo-wallet' ),
                        'type' => 'select',
                        'options' => $usrsArrays
                    ),
                    array(
                        'name' => 'createbutton',
                        'label' => __( 'Create button', 'woo-wallet' ),
                        'default' => '',
                        'desc' => __( 'User Transferbutton from another website.', 'woo-wallet' ),
                        'type' => 'createbutton',
                    )
                    
                    ), array()
                )
            );
            return apply_filters( 'woo_wallet_settings_filds', $settings_fields);
        }

        public function importexportsection(){
            echo 'test';
        }

        /**
         * display plugin settings page
         */
        public function plugin_page() {
            echo '<div class="wrap">';
            echo '<h2 style="margin-bottom: 15px;">' . __( 'Settings', 'woo-wallet' ) . '</h2>';
            settings_errors();
            echo '<div class="wallet-settings-wrap">';
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
            echo '</div>';
            echo '</div>';
        }

        /**
         * Chargeable payment gateways
         * @param string $context
         * @return array
         */
        public function get_wc_payment_gateways( $context = 'field' ) {
            $gateways = array();
            
                
                    
                
                        $gateways[] = array(
                            'name' => 'gateway name 1',
                            'label' => 'label getway 2',
                            'desc' => __( 'Enter gateway charge amount for ', 'woo-wallet' ) . 'title 6',
                            'type' => 'number',
                            'step' => '0.01',
                        );
                
                
            
            return $gateways;
        }

        /**
         * allowed payment gateways
         * @param string $context
         * @return array
         */
        public function get_wc_payment_allowed_gateways( $context = 'field' ) {
            $gateways = array();
            
            $gateways[] = array(
                            'name' => 'Gateway 1 like paypal',
                            'label' => 'Paypal',
                            'desc' => __( 'Allow this gateway for recharge wallet', 'woo-wallet' ),
                            'type' => 'checkbox',
                            'default' => 'on'
            );
                   
            return $gateways;
        }

        /**
         * allowed payment gateways
         * @param string $context
         * @return array
         */
        public function get_wc_tax_options( $context = 'field' ) {
            $tax_options = array();
            
            return $tax_options;
        }

        /**
         * get all registered nav menu locations settings
         * @return array
         */
        public function wp_menu_locations() {
            $menu_locations = array();
            if (current_theme_supports( 'menus' ) ) {
                $locations = get_registered_nav_menus();
                if ( $locations) {
                    foreach ( $locations as $location => $title) {
                        $menu_locations[] = array(
                            'name' => $location,
                            'label' => (current( $locations) == $title) ? __( 'Mini wallet display location', 'woo-wallet' ) : '',
                            'desc' => $title,
                            'type' => 'checkbox'
                        );
                    }
                }
            }
            return $menu_locations;
        }

        /**
         * Callback fuction of all option after save
         * @param array $old_value
         * @param array $value
         * @param string $option
         */
        public function update_option__wallet_settings_general_callback( $old_value, $value, $option) {
            /**
             * Save tax status
             */
            if ( $old_value['_tax_status'] != $value['_tax_status'] || $old_value['_tax_class'] != $value['_tax_class'] ) {
                $this->set_rechargeable_tax_status( $value['_tax_status'], $value['_tax_class'] );
            }

            /**
             * Save product image
             */
            if ( $old_value['product_image'] != $value['product_image'] ) {
                $this->set_rechargeable_product_image( $value['product_image'] );
            }
        }

        

        

    }

    endif;

new Woo_Wallet_Settings(woo_wallet()->settings_api);
