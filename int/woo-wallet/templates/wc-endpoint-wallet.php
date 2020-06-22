<?php
/**
 * The Template for displaying wallet recharge form
 *
 * This template can be overridden by copying it to yourtheme/woo-wallet/wc-endpoint-wallet.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 	Subrata Mal
 * @version     1.1.8
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $wp;
global $content;
global $post;
$teraWallet_key = get_user_meta( get_current_user_id(), 'teraWallet_key', true );
do_action( 'woo_wallet_before_my_wallet_content' );
$is_rendred_from_myaccount = has_shortcode( $content, 'tra-wallet' ) ? false : true;
$menu_items = apply_filters('woo_wallet_nav_menu_items', array(
    'top_up' => array(
        'title' => apply_filters( 'woo_wallet_account_topup_menu_title', __( 'Wallet topup', 'woo-wallet' ) ),
        'url' => get_the_permalink( $post->ID ) . '?wl=top_up',
        'icon' => 'dashicons dashicons-plus-alt'
    ),
    'transfer' => array(
        'title' => apply_filters('woo_wallet_account_transfer_amount_menu_title', __('Wallet transfer', 'woo-wallet')),
        'url' => get_the_permalink( $post->ID ) . '?wl=transfer',
        'icon' => 'dashicons dashicons-randomize'
    ),
    'transaction_details' => array(
        'title' => apply_filters('woo_wallet_account_transaction_menu_title', __('Transactions', 'woo-wallet')),
        'url' => get_the_permalink( $post->ID ),
        'icon' => 'dashicons dashicons-list-view'
    )
), $is_rendred_from_myaccount);




?>

<div class="woo-wallet-my-wallet-container position-relative">
    <div id="user_guide_wrap">
        <div class="inneruser-guide">
            <span class="close-button">
                <img src="<?php echo teraWalletURL; ?>asset/image/remove.svg" alt="<?php _e('Delete button', 'trawallet'); ?>">
            </span>
            <h5 class="mt-1"><?php _e('How to use button?', 'trawallet'); ?></h5>
            <p><?php _e('Very easy to use Trawallet transation button on your own site. YOu should set amount and press "create" button for generate html button.'); ?></p>
            <h5><?php _e('Customize button', 'trawallet'); ?></h5>
            <p><?php _e('Use ".t_submit" class for write your own custom css for change button color, background color, border etc. Below are a example css. You can copy and past it on your own css file.'); ?></p>
            <code>
                &lt;style&gt;<br/>
                    .t_submit{
                        border-width: 3px;
                        border-color:#999;
                        border-radius: 5px;
                        color: #000;
                        font-size: 20px;
                    }<br/>
                &lt;/style&gt;
                </code>
        </div>
    </div>



    <div class="woo-wallet-sidebar">
        <h3 class="woo-wallet-sidebar-heading">
            <a href="<?php echo get_permalink(); ?>"><?php echo apply_filters( 'woo_wallet_account_menu_title', __( 'My Wallet', 'woo-wallet' ) ); ?></a>
        </h3>
        <ul>
            <?php foreach ($menu_items as $item => $menu_item) : ?>
                <?php if (apply_filters('woo_wallet_is_enable_' . $item, true)) : ?>
                    <li class="card"><a href="<?php echo $menu_item['url']; ?>" ><span class="<?php echo $menu_item['icon'] ?>"></span><p><?php echo $menu_item['title']; ?></p></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php do_action('woo_wallet_menu_items'); ?>
        </ul>
    </div>
    <div class="woo-wallet-content">
        <div class="woo-wallet-content-heading">
            <h3 class="woo-wallet-content-h3"><?php _e( 'Balance', 'woo-wallet' ); ?></h3>
            <p class="woo-wallet-price">
                <?php echo woo_wallet()->settings_api->get_option( 'currency_symbol', '_wallet_settings_general', '$' ); ?><?php echo number_format((float)woo_wallet()->wallet->get_wallet_balance( get_current_user_id() ), 2, '.', ''); ?></p>
        </div>
        <div style="clear: both"></div>
        <hr/>

        <?php 
            // echo 'request <br/> <pre>';
            // print_r($_REQUEST);
            // echo '</pre>';
        ?>

<?php 
//     require_once(teraWalletDIR . 'inc/payu_additionalfunction.php');
// if(isset($_POST['payusubmit'])){
//     process_payment(536);
// }

?>
        <?php if ( ( isset( $_REQUEST['wl'] ) && ! empty( $_REQUEST['wl'] ) ) ) { ?>
            <?php if ( isset( $_REQUEST['wl'] ) && 'top_up' === $_REQUEST['wl'] ) { ?>
                <div class="topupform">
                <form method="post" action="">
                    <div class="woo-wallet-add-amount">
                        <div id="topuperror">

                        </div>
                        <label for="woo_wallet_balance_to_add"><?php _e( 'Enter amount', 'woo-wallet' ); ?></label>
                        <?php
                        $min_amount = woo_wallet()->settings_api->get_option( 'min_topup_amount', '_wallet_settings_general', 0 );
                        $max_amount = woo_wallet()->settings_api->get_option( 'max_topup_amount', '_wallet_settings_general', '' );
                        ?>
                        <input type="number" step="0.01" min="<?php echo $min_amount; ?>" max="<?php echo $max_amount; ?>" name="woo_wallet_balance_to_add" id="woo_wallet_balance_to_add" class="woo-wallet-balance-to-add" required="" />
                        <?php wp_nonce_field( 'woo_wallet_topup', 'woo_wallet_topup' ); ?>
                        <!-- <p class="mt-1 mb-0"><label for=""><?php // _e('Payment Type', 'wp_trawallet'); ?></label></h4></p> -->
                        <!-- <input type="submit" name="woo_add_to_wallet" class="woo-add-to-wallet" value="<?php // _e( 'Add', 'woo-wallet' ); ?>" /> -->
                        <input type="submit" name="woo_add_to_wallet" class="woo-add-to-wallet" value="<?php _e( 'Add', 'woo-wallet' ); ?>" />
                    </div>
                </form>
                </div>
                <!-- Include All payment Forms -->
                <div id="paymentForms">
                    <div class="paypal d-none">
                        <?php require_once(WOO_WALLET_ABSPATH . '/templates/paypal-form.php'); ?>
                    </div>

                    <?php if(woo_wallet()->settings_api->get_option('payu', '_wallet_settings_general') == 'on'): ?>
                    <div class="payu d-none">
                        <?php require_once(WOO_WALLET_ABSPATH . '/templates/payu-form.php'); ?>
                    </div>
                    <?php endif; ?>

                    <div class="stripe d-none">
                        <?php require_once(WOO_WALLET_ABSPATH . '/templates/stripe-form.php'); ?>
                    </div>
                    <div class="coinbase_ecommerce_payment d-none">
                    <?php require_once(WOO_WALLET_ABSPATH . '/templates/coinbase-form.php'); ?>
                    </div>
                </div>
                <!-- End all payment Forms -->

            <?php } else if ( apply_filters( 'woo_wallet_is_enable_transfer', 'on' === woo_wallet()->settings_api->get_option( 'is_enable_wallet_transfer', '_wallet_settings_general', 'on' ) ) && ( ( isset( $_REQUEST['wl'] ) && 'transfer' === $_REQUEST['wl'] ) ) ) { ?> 
               
               <div class="form-group display-inline">
                    <label for="transver1">
                        <input type="radio" checked name="transver" value="transfer" id="transver1"> <?php _e('Transfer', 'trawallet'); ?>
                    </label>
                    <label for="transver2">
                        <input type="radio" name="transver" value="csv_upload" id="transver2"> <?php _e('CSV Upload', 'trawallet'); ?>
                    </label>
                    <label for="transver3">
                        <input type="radio" name="transver" value="create_button" id="transver3"> <?php _e('Create Button', 'trawallet'); ?>
                    </label>
               </div>
               
               <div id="transver">
               <form method="post" action="">
                    <p style="float:left; margin-top:5px;" class="woo-wallet-field-container form-row form-row-wide w-100 d-block">
                        <label for="woo_wallet_transfer_user_id"><?php _e( 'Select whom to transfer', 'woo-wallet' ); ?> <?php
                            // if ( apply_filters( 'woo_wallet_user_search_exact_match', true ) ) {
                            //     _e( '(Email)', 'woo-wallet' );
                            // }
                            ?></label>
                        <!-- <select name="woo_wallet_transfer_user_id" class="woo-wallet-select2" required=""></select> -->
                        <input type="number" name="user_sk" placeholder="<?php _e('User SK', 'trawallet'); ?>" id="user_sk" class="user_sk form-control w-100">
                    </p>
                    <p class="woo-wallet-field-container form-row form-row-wide">
                        <label for="woo_wallet_transfer_amount"><?php _e( 'Amount', 'woo-wallet' ); ?></label>
                        <input class="form-control w-100" placeholder="<?php _e('Amount', 'trawallet'); ?>" type="number" step="0.01" min="<?php echo woo_wallet()->settings_api->get_option('min_transfer_amount', '_wallet_settings_general', 0); ?>" name="woo_wallet_transfer_amount" required=""/>
                    </p>
                    <p class="woo-wallet-field-container form-row form-row-wide">
                        <label for="woo_wallet_transfer_note"><?php _e( 'Description', 'woo-wallet' ); ?></label>
                        <textarea placeholder="<?php _e('Description', 'trawallet'); ?>" name="woo_wallet_transfer_note"></textarea>
                    </p>
                    <p class="woo-wallet-field-container form-row">
                        <?php wp_nonce_field( 'woo_wallet_transfer', 'woo_wallet_transfer' ); ?>
                        <input type="submit" class="button" name="woo_wallet_transfer_fund" value="<?php _e( 'Proceed to transfer', 'woo-wallet' ); ?>" />
                    </p>
                </form>
               </div>
               <div id="csvTransfer">
                    <div id="uploadcsv" class="form-group">
                        <label for="csv_upload"><?php _e('CSV Upload', 'trawallet'); ?></label>
                        <a download href="<?php echo teraWalletURL . 'asset/csv/demo-trawallet.csv'; ?>" id="downloadcsv"><?php _e('Download Demo CSV', 'trawallet'); ?></a>
                        <input type="file" name="csv_upload" id="csv_upload">
                    </div>
               </div>
               <!-- Create button  -->
               <div id="CreateButton">
                    <div id="createbuttoninner">
                        <div class="form-groiup">
                            <label for="transfarable_amount"><?php _e('Amount', 'trawallet'); ?></label><br>
                            <input type="number" min="0" class="form-control" name="transfarable_amount" id="transfarable_amount">
                        </div>

                        <div id="createbutton">
                        <div class="errormsg"></div>
                        <div class="createbuttonForm form-group">
                                <button class="mb-1 mt-1 button button-primary" id="createform"><?php _e('Create Button', 'trawallet'); ?></button>
                                &nbsp;<a id="user_guide" class="ml-1" href="javascript:void(0)"><?php _e('User Guid', 'trawallet'); ?></a>
                                <textarea class="form-control d-none" data-id="<?php echo esc_attr( $teraWallet_key[0] ); ?>" data-url="<?php echo get_home_url('/'); ?>" name="trawallet_transation" id="transation" style="width:100%" rows="10"></textarea>
                        </div>
                    </div>
                    </div>
               </div>

            <?php } ?> 
            <?php do_action( 'woo_wallet_menu_content' ); ?>
        <?php } else if ( apply_filters( 'woo_wallet_is_enable_transaction_details', true ) ) { ?>
            <?php $transactions = get_wallet_transactions( array( 'limit' => apply_filters( 'woo_wallet_transactions_count', 10 ) ) ); ?>
            <?php
            if ( ! empty( $transactions ) && !isset($_REQUEST['status']) ) { ?>
                <ul class="woo-wallet-transactions-items">
                    <?php foreach ( $transactions as $transaction ) : ?> 
                        <li>
                            <div>
                                <p><?php echo $transaction->details; ?></p>
                                <small><?php echo $transaction->date; ?></small>
                            </div>
                            <div class="woo-wallet-transaction-type-<?php echo $transaction->type; ?>"><?php
                                echo $transaction->type == 'credit' ? '+' : '-';
                                echo  apply_filters( 'woo_wallet_amount', $transaction->amount, $transaction->currency, $transaction->user_id );
                                ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php
            }elseif(isset($_REQUEST['status']) && $_REQUEST['status'] == 'success'){ ?>
                <div id="transationsuccess">
                    <div class="successmsg">
                        <h4 class="text-center"><?php _e('Transaction success', 'wp_trawallet'); ?></h4>
                    </div>
                </div>
            <?php }
            else {
                _e( 'No transactions found', 'woo-wallet' );
            }
        }
        ?>
    </div>
</div>
<?php do_action( 'woo_wallet_after_my_wallet_content' );
