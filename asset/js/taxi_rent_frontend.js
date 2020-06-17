jQuery(document).ready(function(e){
    'use strict';
    jQuery(document).on('click', 'a.online-payment', function(e){
        e.preventDefault();
        var bodywidth = jQuery(document.body).width();
        jQuery('div#online-booking-form').fadeIn('slow', function(){
          var formwidth = jQuery('div#online-booking-form > div.form-online-inner').outerWidth(),
          marginLeft = (bodywidth - formwidth) / 2;
          jQuery('div#online-booking-form > div.form-online-inner').animate({
            marginLeft: marginLeft + 'px' 
          })
        });
    });
});