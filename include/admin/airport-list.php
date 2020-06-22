<h4 class="mt-3 mb-3"><?php _e('Airport & Seaport List', 'taxi-rent'); ?></h4>
<div id="airportList">
    <div id="innerList">
        <div class="list-inner background-white">
        <ul id="ul_inner_list">
        <?php if($portLists = get_option('portlists')): 
                $portLists = json_decode($portLists);
                $portLists = (array)$portLists;

                foreach($portLists as $k => $singlePort){
                    echo '<li data-place_id="'.$k.'"><span class="address">'.$singlePort.'</span><span class="delete_from_list"><span class="dashicons dashicons-dismiss"></span></span></li>';
                }
        ?>
        <?php endif; ?>
        </ul>
        </div>    
    </div>

    <div class="addFormlist">
        <div class="form-group">
            <label for="add_address"><?php _e('Add Address to list', 'taxt-rent'); ?></label>
            <div class="input-group mb-3">
                <input id="add_address" class="form-control" type="text" name="add_address">
                <input type="hidden" name="place_id" id="place_id">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" onClick="addtoList()" id="addAddressButton" type="button"><span class="pt-4-px dashicons dashicons-plus"></span></button>
                </div>
            </div>
        </div>
    </div>
</div>    



<script>
function initMap() {
    var add_address = document.getElementById('add_address');
    var place = new google.maps.places.Autocomplete(
    add_address, {
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