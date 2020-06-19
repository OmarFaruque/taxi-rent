jQuery(document).ready(function(e){
    'use strict';


    if(jQuery('div#online-booking-form').length){
      var bodyheight = window.innerHeight,
      formHeight = bodyheight - 100;


      jQuery('div#online-booking-form > div.form-online-inner').css('margin-top', '50px');
      jQuery('div#online-booking-form > div.form-online-inner').css('max-height', formHeight + 'px');

    }
    


    jQuery(document).on('click', 'a.online-payment', function(e){
        var rent_amount = jQuery(this).data('amount')
        e.preventDefault();
        var bodywidth = jQuery(document.body).width();
        jQuery('div#online-booking-form').fadeIn('slow', function(){
          var form = jQuery('div#online-booking-form > div.form-online-inner form'),
          formwidth = jQuery('div#online-booking-form > div.form-online-inner').outerWidth(),
          marginLeft = (bodywidth - formwidth) / 2;
          form.find('input[name="taxi_rent_amount"]').val(rent_amount);
          jQuery('div#online-booking-form > div.form-online-inner').animate({
            marginLeft: marginLeft + 'px' 
          })
        });
    });


    // PayNow submit
    jQuery(document.body).on('click', 'input[name="pay_now"]', function(){
      var newInput = '<input name="submit_type" value="pay_now" type="hidden"/>',
      form = jQuery(this).closest('form');
      form.append(newInput);
      form.submit();
    });
});