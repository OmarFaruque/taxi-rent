var addtoList = function(){
    var inputval = jQuery('#add_address').val(),
    place_id = jQuery('input[name="place_id"]').val();


    var data = {
        'action': 'addNewAirportListByAjax',
        'address': inputval,
        'place_id': place_id
    };

    jQuery.post(admin_ajax.ajaxurl, data, function(response) {
        if(response.msg == 'success'){
            jQuery('ul#ul_inner_list').append('<li><span class="address">'+inputval+'</span><span class="delete_from_list"><span class="dashicons dashicons-dismiss"></span></span></li>');
            inputval.val('');
        }
    });
}


jQuery(document).ready(function(){
    jQuery(document).on('click', 'span.delete_from_list', function(){
        var place_id = jQuery(this).closest('li').data('place_id'),
        thisli = jQuery(this).closest('li'),
        addressField = jQuery('input#add_address');


        var data = {
            'action': 'deleteAirportListItemByAjax',
            'place_id': place_id
        };

        jQuery.post(admin_ajax.ajaxurl, data, function(response) {
            if(response.msg == 'success'){
                thisli.remove();        
                
            }
        });


    });
});