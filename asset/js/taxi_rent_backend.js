jQuery(document).ready(function(){

    /*
    * Add airport list submit
    */
   jQuery(document).on('click', 'button#addAddressButton', function(e){
        e.preventDefault();
        var thisForm = jQuery(this).closest('form');
        var port_a = jQuery(this).closest('form').find('input#port_a').val(),
        port_b = jQuery(this).closest('form').find('input#port_b').val(),
        price = jQuery(this).closest('form').find('input#price').val(),
        place_id = jQuery('#ul_inner_list tbody tr:last-child').data('place_id');
        place_id = parseInt(place_id) + 1;


        
        var newHtml = '<tr data-place_id="'+place_id+'">'
        +'<td>'+port_a+'</td>'
        +'<td>'+port_b+'</td>'
        +'<td>'+price+'</td>'
        +'<td>'
        +'<a href="#" data-id="'+place_id+'" class="delete_port">Delete</a>'
        +'</td>'
        +'</tr>';
   
        var data = {
            'action': 'addNewAirportListByAjax',
            'port_a': port_a,
            'port_b': port_b,
            'price' : price
        };
    
        jQuery.post(admin_ajax.ajaxurl, data, function(response) {
            
            if(response.msg == 'success'){
                jQuery('table#ul_inner_list tbody').append(newHtml);
                thisForm.find('input[type="text"]').val('');
                thisForm.find('input[type="number"]').val('');
            }
        });
   });



    jQuery(document).on('click', 'a.delete_port', function(e){
        e.preventDefault();
        var place_id = jQuery(this).data('id'),
        thisli = jQuery(this).closest('tr');


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