function popup_icon_picker(){
    jQuery( '.icp' ).iconpicker().on( 'iconpickerUpdated', function() {
        jQuery( this ).trigger( 'change' );
    } );
}