<?php 
/*
* Settings Page
*/

// Get option frolm DB 
$local_service = get_option( 'local_service', 1 );
$airport_seaport = get_option( 'airport_seaport', 1 );
$hourly_rent = get_option( 'hourly_rent', 1 );
$quote_page = get_option('quote_page');
$taxi_vat = get_option('taxi_vat');
$map_api = get_option('map_api', 'AIzaSyDIvHe8zwX9-D5YE39wEAqseTtsRP7EyvQ');

?>
<div id="taxi_wrap" class="pt-3 bg-white">
<?php
//Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

  ?>
  <!-- Our admin page content should all be inside .wrap -->
  <div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      <a href="<?php echo admin_url('edit.php?post_type=vehicle&page=taxi-settings'); ?>" class="nav-tab <?php echo $tab == null ? 'nav-tab-active':''; ?>"><?php _e('General', 'taxi-rent'); ?></a>
      <a href="<?php echo admin_url('edit.php?post_type=vehicle&page=taxi-settings&tab=airport-list'); ?>" class="nav-tab"><?php _e('Airport List', 'taxi-rent'); ?></a>
    </nav>






    <div class="tab-content">
    <?php switch($tab) :
        case 'airport-list':
            require_once($this->plugin_path . 'include/admin/airport-list.php');     
        break;
        default:
            require_once($this->plugin_path . 'include/admin/general-settings.php'); 
    endswitch; ?>
    </div>
  </div>

</div>




