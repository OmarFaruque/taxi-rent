jQuery(document).ready(function(e){
    'use strict';




  /*
    * Drop of destination
  */
if(jQuery('.adddropoff > span.addDropOffButton').length){
    jQuery(document).on('click', '.adddropoff > span.addDropOffButton', function(e){
      e.preventDefault();
      var thisClick = jQuery(this);
      jQuery(this).closest('div.adddropoff').prev('div#stop_address').slideToggle("slow","swing", function(){

        if(thisClick.find('i.fa').hasClass('fa-plus')){
          thisClick.find('i.fa').removeClass('fa-plus').addClass('fa-minus');
          jQuery(this).find('input').prop('disabled', false);
          jQuery('input#drop_off_place_id').prop('disabled', false);
        }else{
          thisClick.find('i.fa').removeClass('fa-minus').addClass('fa-plus');
          jQuery(this).find('input').prop('disabled', true);
          jQuery('input#drop_off_place_id').prop('disabled', true);
        }

      });

      
    });
}




  /*
  * Back button for choose additional service
  * Target element in booking-quote.php
  */  
 if(jQuery('button.backtochoose').length){
   jQuery(document).on('click', 'button.backtochoose', function(e){
      e.preventDefault();
      jQuery('div#online-booking-form').find('div.part-one').removeClass('d-none');
      jQuery('div#online-booking-form').find('div.part-tow').addClass('d-none');  
   });
 }


  
  /*
  * Next button
  **/
  if(jQuery('button.booking-form.next').length){
    jQuery(document).on('click', 'button.booking-form.next', function(e){
      e.preventDefault();
      jQuery('div#online-booking-form').find('div.part-one').addClass('d-none');
      jQuery('div#online-booking-form').find('div.part-tow').slideUp('slow', function(){
          jQuery('div#online-booking-form').find('div.part-tow').removeClass('d-none');  
      });
    });
  }




    /*
    * Step 2 Add additional Service fee
    */
   if(jQuery('form#other_service').length){
    jQuery('form#other_service input').change(function(){
          var thisprice = jQuery(this).closest('div.toggle').prev('h6').find('span').text();
          var price = jQuery('strong.priceAfterAddService').text();
              
          if(jQuery(this).is(':checked')){
            var newPrice = +price + +thisprice;
          }else{
            var newPrice = +price + -thisprice;
          }
          jQuery('strong.priceAfterAddService').text(newPrice.toFixed(2));
          jQuery('input[name="taxi_rent_amount"]').val(newPrice.toFixed(2));
          
      });
    }



    if(jQuery('div#online-booking-form').length){
      var bodyheight = window.innerHeight,
      formHeight = bodyheight - 100;

      jQuery('div#online-booking-form > div.form-online-inner').css('margin-top', '50px');
      jQuery('div#online-booking-form > div.form-online-inner').css('max-height', formHeight + 'px');

    }
    
    jQuery(document).on('click', 'a.online-payment', function(e){
        var rent_amount = jQuery(this).data('amount'),
        vehicle_id = jQuery(this).data('post_id');
        e.preventDefault();
        var bodywidth = jQuery(document.body).width();

        jQuery('form#other_service span.baby_over_5').text( jQuery(this).data('baby_over_5'));
        jQuery('form#other_service span.baby_under_5').text( jQuery(this).data('baby_under_5'));
        jQuery('form#other_service span.meet_n_greet').text( jQuery(this).data('meet_n_greet'));
        jQuery('form#other_service span.car_park').text( jQuery(this).data('car_park'));


        jQuery('div#online-booking-form').fadeIn('slow', function(){
          var form = jQuery('div#online-booking-form > div.form-online-inner form'),
          formwidth = jQuery('div#online-booking-form > div.form-online-inner').outerWidth(),
          marginLeft = (bodywidth - formwidth) / 2;
          form.find('input[name="taxi_rent_amount"]').val(rent_amount);
          form.find('input[name="vehicle_id"]').val(vehicle_id);
          
          jQuery('strong.priceAfterAddService').text(rent_amount);
          jQuery('div#online-booking-form > div.form-online-inner').animate({
            marginLeft: marginLeft + 'px' 
          })
        });
    });


    // PayNow submit
    jQuery(document.body).on('click', 'input[name="pay_now"]', function(e){
      var newInput = '<input name="submit_type" value="pay_now" type="hidden"/>',
      form = jQuery(this).closest('form');
      form.append(newInput);
      form.submit();
    });

    jQuery(document.body).on('click', 'input[name="pay_later"]', function(e){
      e.preventDefault();
      jQuery('div#online-booking-form').fadeOut('slow', function(){
        jQuery('div#online-booking-form > div.form-online-inner').css('margin-left', '-100%')
      });
    });


    // Switch functionality
    jQuery(document.body).on('click', 'div#switchDirection img', function(){
      var pickupSelect = jQuery('select#pickup_airport_select'),
      pickupInput = jQuery('input#pickup_airport'),
      destinationSelect = jQuery('select#destination_airport_select'),
      destinationInput = jQuery('input#destination_airport');

      if(destinationInput.is(':disabled')){
          pickupInput.prop('disabled', true);
          pickupInput.closest('div').addClass('d-none');
          destinationInput.prop('disabled', false);
          destinationInput.closest('div').removeClass('d-none');
      }else{
        pickupInput.prop('disabled', false);
        pickupInput.closest('div').removeClass('d-none');
        destinationInput.prop('disabled', true);
        destinationInput.closest('div').addClass('d-none');
      }

    })
});



/*
* Windows load
*/
jQuery(window).on('load', function(){ 

});