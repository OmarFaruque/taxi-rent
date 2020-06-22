<h4 class="mt-3"><?php _e('General', 'taxi-rent'); ?></h4>
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
                    <div class="form-group">
                        <select name="quote_page" class="form-control" id="quote_page">
                            <option value=""><?php _e('Select a page as Quote', 'taxi-rent'); ?></option>
                            <?php
                                foreach(get_all_page_ids() as $spage){
                                    $selected = ($quote_page == $spage) ? 'selected':'';
                                    echo '<option '.$selected.' value="'.$spage.'">'. get_the_title($spage) .'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                <label for="taxi_vat">
                    <?php _e('Vat as Percentage(%)', 'taxi-rent'); ?>&nbsp; </label>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" value="<?php echo $taxi_vat; ?>" min="0" step="0.01" name="taxi_vat" id="taxi_vat" class="form-control">
                    </div>
                </td>
            </tr>

            <!-- Google Map Api -->
            <tr>
                <td>
                <label for="taxi_vat">
                    <?php _e('Google Map API', 'taxi-rent'); ?>&nbsp; </label>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" value="<?php echo $map_api; ?>" name="map_api" id="map_api" class="form-control">
                    </div>
                </td>
            </tr>

        </table>

        
        
        <br>
        <input type="submit" name="taxi_settings_button" class="btn btn-primary" value="<?php _e('Submit', 'taxi-rent'); ?>">
    </form>