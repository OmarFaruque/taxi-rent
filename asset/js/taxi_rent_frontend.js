jQuery(document).ready(function(e){
    'use strict';
    jQuery(document).on('click', 'a.online-payment', function(e){
        e.preventDefault();
        var dialog;
        dialog = jQuery( "#dialog-form" ).dialog({
            autoOpen: false,
            height: 400,
            width: 350,
            modal: true,
            buttons: {
              Cancel: function() {
                dialog.dialog( "close" );
              }
            },
            close: function() {
              form[ 0 ].reset();
              allFields.removeClass( "ui-state-error" );
            }
        });
    });
});