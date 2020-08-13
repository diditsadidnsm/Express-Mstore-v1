jQuery(document).ready(function($) {

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('.wpuf-date-range').hide();

    $('#wpuf-user-dropdown').change(function(){
        $('.wpuf-date-range').hide();
        $('#' + $(this).val()).show();
    });

    $('#wpuf-post-dropdown').change(function(){
        $('.wpuf-date-range').hide();
        $('#' + $(this).val()).show();
    });

    $('#wpuf-subs-dropdown').change(function(){
        $('.wpuf-date-range').hide();
        $('#' + $(this).val()).show();
    });

    $('#wpuf-transaction-dropdown').change(function(){
        $('.wpuf-date-range').hide();
        $('#' + $(this).val()).show();
    });

    $(function() {
        $('form').submit(function() {
            $('form input').each(function() {
                if ($(this).val().length == 0) {
                    $(this).attr('disabled', true);
                }
            });
        });
    });

    loadChartJsPhp();

});
