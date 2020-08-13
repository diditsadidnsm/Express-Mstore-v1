;(function($){

    $('.qr_template img').on('click',function(){
        var template_id = $(this).data('templateid');
        $('#template_id_input').val(template_id);

        $(this).addClass('selected').siblings($(this)).removeClass('selected');
    })

})(jQuery)