<?php 
/*
* Settings Page
*/

// Get option frolm DB 
$local_service = get_option( 'local_service', 1 );
$airport_seaport = get_option( 'airport_seaport', 1 );
$hourly_rent = get_option( 'hourly_rent', 1 );
$quote_page = get_option('quote_page');

?>
<div id="taxi_wrap" class="pt-3 bg-white">
    <h2><?php _e('Settings', 'taxi-rent'); ?></h2>
    <form class="settings-form" action="" method="post">

        <table class="table">
            <tr>
                <td>
                    <label for="local_service"><?php _e('Local Service', 'taxi-rent'); ?></label>
                </td>
                <td>
                    <input name="local_service" id="local_service" type="checkbox" <?php echo ($local_service) ? 'checked': ''; ?> data-toggle="toggle" data-size="sm">
                </td>
            </tr>

            <tr>
                <td>
                <label for="airport_seaport">
                    <?php _e('Airport & Seaport', 'taxi-rent'); ?>
                </label>
                </td>
                <td>
                    <input name="airport_seaport" id="airport_seaport" type="checkbox" <?php echo ($airport_seaport) ? 'checked': ''; ?> data-toggle="toggle" data-size="sm">
                </td>
            </tr>


            <tr>
                <td>
                <label for="hourly_rent">
                    <?php _e('Hourly Rent', 'taxi-rent'); ?>&nbsp; </label>
                </td>
                <td>
                <input name="hourly_rent" id="hourly_rent" type="checkbox" <?php echo ($hourly_rent) ? 'checked': ''; ?> data-toggle="toggle" data-size="sm">    
                </td>
            </tr>

            <tr>
                <td>
                <label for="quote_page">
                    <?php _e('Select a Quote Page', 'taxi-rent'); ?>&nbsp; </label>
                </td>
                <td>
                    
                    <select name="quote_page" class="form-control" id="quote_page">
                        <option value=""><?php _e('Select a page as Quote', 'taxi-rent'); ?></option>
                        <?php
                            foreach(get_all_page_ids() as $spage){
                                $selected = ($quote_page == $spage) ? 'selected':'';
                                echo '<option '.$selected.' value="'.$spage.'">'. get_the_title($spage) .'</option>';
                            }
                        ?>
                    </select>
                </td>
            </tr>
        
        </table>

        
        
        <br>
        <input type="submit" name="taxi_settings_button" class="btn btn-primary" value="<?php _e('Submit', 'taxi-rent'); ?>">
    </form>
</div>
