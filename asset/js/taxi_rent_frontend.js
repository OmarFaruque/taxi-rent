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


    // Switch functionality
    jQuery(document.body).on('click', 'div#switchDirection img', function(){
      var pickupSelect = jQuery('select#pickup_airport_select'),
      pickupInput = jQuery('input#pickup_airport'),
      destinationSelect = jQuery('select#destination_airport_select'),
      destinationInput = jQuery('input#destination_airport');

      if(pickupSelect.is(':disabled')){
          pickupSelect.prop('disabled', false);
          pickupInput.prop('disabled', true);
          destinationSelect.prop('disabled', true);
          destinationInput.prop('disabled', false);
      }else{
          pickupSelect.prop('disabled', true);
          pickupInput.prop('disabled', false);
          destinationSelect.prop('disabled', false);
          destinationInput.prop('disabled', true);
      }

    })


});