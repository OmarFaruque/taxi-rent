jQuery(function(e){e("#wc-wallet-transaction-details").DataTable({responsive:!0,searching:!1,order:[[0,"desc"]],language:{emptyTable:wallet_param.i18n.emptyTable,lengthMenu:wallet_param.i18n.lengthMenu,info:wallet_param.i18n.info,infoEmpty:wallet_param.i18n.infoEmpty,paginate:wallet_param.i18n.paginate}}),e(".woo-wallet-select2").selectWoo({language:{inputTooShort:function(){return wallet_param.search_by_user_email?wallet_param.i18n.non_valid_email_text:wallet_param.i18n.inputTooShort},noResults:function(){return wallet_param.search_by_user_email?wallet_param.i18n.non_valid_email_text:wallet_param.i18n.no_resualt},searching:function(){return wallet_param.i18n.searching}},minimumInputLength:3,ajax:{url:wallet_param.ajax_url,dataType:"json",type:"POST",quietMillis:50,data:function(a){return{action:"woo-wallet-user-search",autocomplete_field:"ID",term:a.term}},processResults:function(a){return{results:e.map(a,function(a){return{id:a.value,text:a.label}})}}}})});