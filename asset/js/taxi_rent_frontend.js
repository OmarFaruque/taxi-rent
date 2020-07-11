function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

jQuery(document).ready(function(){
    'use strict';



   var addCol = function(){
      if(jQuery('div#vaclelist').width() < 600 ){
        jQuery('div#vaclelist').addClass('two_col');
      }else{
        jQuery('div#vaclelist').removeClass('two_col');
      }
   }
   
   if(jQuery('div#vaclelist').length){
      addCol();
   }
   jQuery(window).resize(function(){
    addCol();
   });

    
    



    jQuery(document.body).on('click', 'a.online-payment', function(e){
      e.preventDefault();
        var rent_amount = jQuery(this).data('amount'),
        vehicle_id = jQuery(this).data('post_id');
        
        var bodywidth = jQuery(document.body).width();
        
        jQuery('form#other_service span.baby_over_5').text( jQuery(this).data('baby_over_5'));
        jQuery('form#other_service span.baby_under_5').text( jQuery(this).data('baby_under_5'));
        jQuery('form#other_service span.meet_n_greet').text( jQuery(this).data('meet_n_greet'));
        jQuery('form#other_service span.car_park').text( jQuery(this).data('car_park'));
        jQuery('img.child_over_5').attr('src', jQuery(this).data('baby_set_over_5_img'));
        jQuery('img.child_under_5').attr('src', jQuery(this).data('baby_set_under_5_img'));
        jQuery('img.meat_n_greet').attr('src', jQuery(this).data('meet_n_greet_img'));
        jQuery('img.car_park').attr('src', jQuery(this).data('car_park_img'));


        jQuery('div#online-booking-form').fadeIn('slow', function(){
          var form = jQuery('div#online-booking-form > div.form-online-inner form'),
          formwidth = jQuery('div#online-booking-form > div.form-online-inner').outerWidth(),
          marginLeft = (bodywidth > 768) ? (bodywidth - formwidth) / 2 : 0;
          form.find('input[name="taxi_rent_amount"]').val(rent_amount);
          form.find('input[name="vehicle_id"]').val(vehicle_id);
          
          jQuery('strong.priceAfterAddService').text(rent_amount);
          jQuery('div#online-booking-form > div.form-online-inner').animate({
            marginLeft: marginLeft + 'px' 
          })
        });
    });


    /*
    * Step 2 Add additional Service fee
    */
   if(jQuery('form#other_service').length){
    jQuery('form#other_service input').change(function(){
          var thisprice = jQuery(this).closest('div.additionalService').find('h6').find('span').text();
          var price = jQuery('strong.priceAfterAddService').text();
          price = price.replace(/,/g, '');
    
          if(jQuery(this).is(':checked')){
            var newPrice = +price + +thisprice;          
          }else{
            var newPrice = +price + -thisprice;
          }

          newPrice = newPrice.toFixed(2);

          newPrice = numberWithCommas(newPrice);
          
          jQuery('strong.priceAfterAddService').text(newPrice);
          jQuery('input[name="taxi_rent_amount"]').val(newPrice);
          
      });
    }




  if(jQuery('div#online-booking-form').length){
    var bodyheight = window.innerHeight,
    formHeight = bodyheight - 100;
    jQuery('div#online-booking-form > div.form-online-inner').css('margin-top', '50px');
    jQuery('div#online-booking-form > div.form-online-inner').css('max-height', formHeight + 'px');
  }


  /*
    * Next button
  **/
  if(jQuery('button.booking-form.next').length){
    console.log('tst omar');
    jQuery(document.body).on('click', 'button.booking-form.next', function(e){
      e.preventDefault();
      jQuery('div#online-booking-form').find('div.part-one').addClass('d-none');
      jQuery('div#online-booking-form').find('div.part-tow').slideUp('slow', function(){
          jQuery('div#online-booking-form').find('div.part-tow').removeClass('d-none');  
      });
    });
  }


  /*
  * Hide Payment Form
  */
 jQuery(document.body).on('click', 'div#removeContinue', function(e){
    jQuery('div#online-booking-form').fadeOut('slow', function(){
    });
 });


  /*
  * Date picker
  */
  if(jQuery('input#travel_date_time').length){
    jQuery('input#travel_date_time').datepicker();
  }

  /*
  * Form Validattion 
  */

 if(jQuery("#comfirmByPayment").length) { 
    jQuery("#comfirmByPayment").validate();
 }

  /*
    * Drop of destination
  */
if(jQuery('.adddropoff > span.addDropOffButton').length){
    jQuery(document).on('click', '.adddropoff > span.addDropOffButton', function(e){
      e.preventDefault();
      var length = jQuery(this).closest('div.adddropoff').prev('div#stop_address').find('.single-via').length;
      
      if(length < 3){
        var className = (jQuery(this).hasClass('local')) ? 'drop_off' : 'drop_off_port';
        var html = '<div class="single-via mb-1">'
        +'<input type="text" class="w-100 '+className+'" placeholder="Stop Address" name="drop_off[]">'
        +'<span class="delete_via"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </span>'
        +'</div>';
        var thisClick = jQuery(this);
        jQuery(this).closest('div.adddropoff').prev('div#stop_address').show();
        jQuery(this).closest('div.adddropoff').prev('div#stop_address').append(html);
      }

       // AirportDorp field 
      
      var drop_off_port           = (!jQuery(this).hasClass('local')) ? document.getElementsByClassName('drop_off_port') : document.getElementsByClassName('drop_off');
      for(var i=0; i < drop_off_port.length; i++ ){
        var drop_off_port_Autocomplete = new google.maps.places.Autocomplete(
          drop_off_port[i], {
            fields: ['place_id', 'name', 'types']
        });
      }

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
    * Payment Option resize while window resize
    */
    jQuery(window).resize(function(){
      var bodywidth = jQuery(document.body).width(),
      formwidth = jQuery('div#online-booking-form > div.form-online-inner').outerWidth(),
      marginLeft = (bodywidth > 768) ? (bodywidth - formwidth) / 2 : 0;
      jQuery('div#online-booking-form > div.form-online-inner').animate({
        marginLeft: marginLeft + 'px' 
      })
    });


    // PayNow submit
    jQuery(document.body).on('click', 'input[name="pay_now"], input[name="pay_later"]', function(e){
      var newInput = '<input name="submit_type" value="pay_now" type="hidden"/>',
      form = jQuery(this).closest('form');
      form.append(newInput);
      form.submit();
    });

    // jQuery(document.body).on('click', 'input[name="pay_later"]', function(e){
    //   e.preventDefault();
    //   jQuery('div#online-booking-form').fadeOut('slow', function(){
    //     jQuery('div#online-booking-form > div.form-online-inner').css('margin-left', '-100%')
    //   });
    // });


    // Switch functionality
    jQuery(document.body).on('click', 'div#switchDirection', function(){
      // console.log('sss');
      var swap = jQuery('input[name="swap"]');
      if(swap.val() == 'town_to_port'){
        swap.val('port_to_town');
        jQuery('#townSelection').insertAfter('div#dropAreea');
        jQuery('#portListsArea').insertBefore('div#dropAreea');
      }else{
        swap.val('town_to_port');
        jQuery('#townSelection').insertBefore('div#dropAreea');
        jQuery('#portListsArea').insertAfter('div#dropAreea');
      }
      
      

    });
}); // Document load end



/*
* Windows load
*/
jQuery(window).on('load', function(){ 

});