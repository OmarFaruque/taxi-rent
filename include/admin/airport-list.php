<h4 class="mt-3 mb-3"><?php _e('Airport & Seaport List', 'taxi-rent'); ?></h4>
<div id="airportList">
    <div id="innerList">
        <div class="list-inner background-white">
        <table class="table table-striped" id="ul_inner_list">
            <thead class="thead-dark">
                <tr>
                    <th><?php _e('Town List', 'taxi-rent'); ?></th>
                    <th><?php _e('Port List', 'taxi-rent'); ?></th>
                    <th><?php _e('Price', 'taxi-rent'); ?></th>
                    <th><?php _e('Action', 'taxi-rent'); ?></th>
                </tr>
            </thead>
        <?php if($portLists = get_option('portlists')): 
                $portLists = json_decode($portLists);
                $portLists = (array)$portLists;

                // echo 'Omar<pre>';
                // print_r($portLists);
                // echo '</pre>';

                foreach($portLists as $k => $singlePort){ ?>
                    <tr data-place_id="<?php echo $k; ?>">
                        <td><?php echo $singlePort->port_a;  ?></td>
                        <td><?php echo $singlePort->port_b;  ?></td>
                        <td><?php echo $singlePort->price;  ?></td>
                        <td>
                            <a href="#" data-id="<?php echo $k; ?>" class="delete_port"><?php _e('Delete', 'taxi-rent'); ?></a>
                        </td>
                    </tr>
                <?php }
        ?>
        <?php endif; ?>
        </table>
        </div>    
    </div>

    <div class="addFormlist mt-5">
        <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            
                        
                            <form id="addPortList" action="" method="post">
                                <label for="port_a"><?php _e('Add Port List', 'taxt-rent'); ?></label>
                                <div class="input-group mb-3">
                                    <input required id="port_a" class="form-control" placeholder="<?php _e('Town Name', 'taxi-rent'); ?>" type="text" name="port_a">
                                    <input required id="port_b" class="form-control" placeholder="<?php _e('Port Name', 'taxi-rent'); ?>" type="text" name="port_b">
                                    <input required id="price" class="form-control" type="number" placeholder="<?php _e('Price', 'taxi-rent'); ?>" name="port_price">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-secondary" id="addAddressButton" type="button"><span class="pt-4-px dashicons dashicons-plus"></span></button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>

                    

        </div>
    </div>


</div>    



<script>
function initMap() {
    var add_address = document.getElementById('port_a'),
    port_b = document.getElementById('port_b');
    
    var place = new google.maps.places.Autocomplete(
    add_address, {
      fields: ['place_id', 'name', 'types']
    });

    var place_b = new google.maps.places.Autocomplete(
    port_b, {
      fields: ['place_id', 'name', 'types']
    });
    
    google.maps.event.addListener(place, 'place_changed', function() {
    var details = place.getPlace();
    document.getElementById('place_id').value = details.place_id;

    });
// 
    

}
</script>
<?php wp_enqueue_script( 'taxi-google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDIvHe8zwX9-D5YE39wEAqseTtsRP7EyvQ&libraries=places&callback=initMap', time(), true ); ?>