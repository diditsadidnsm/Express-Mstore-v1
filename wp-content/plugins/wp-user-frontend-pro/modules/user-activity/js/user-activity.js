jQuery(document).ready(function($){
    $('ul.wpuf-profile-tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.wpuf-profile-tabs li').removeClass('current');
        $('.wpuf-profile-tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    })
})