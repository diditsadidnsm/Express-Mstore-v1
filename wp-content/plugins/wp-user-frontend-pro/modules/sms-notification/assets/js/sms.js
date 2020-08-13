jQuery(function($) {
    $("input.wpuf-sms-enable").click(function(){
        if($(this).prop("checked")) {
            $('.wpuf-sms-wrap').show();
        } else {
            $('.wpuf-sms-wrap').hide();
        }
    });
});
