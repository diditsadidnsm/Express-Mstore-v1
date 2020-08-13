(function($) { 

    // Update base state field based on selected base country
    $( document.body ).on('change', 'select#wpuf_base_country', function() {
        var $this = $(this), $tr = $this.closest('tr');
        var data = {
            action: 'wpuf_get_base_states',
            country: $(this).val()
        };
        $.post(ajaxurl, data, function (response) {
            if( 'nostates' == response.data ) {
                var text_field = '<input type="text" name="' + data.field_name + '" value=""/>';
                $this.parent().next().find('select').replaceWith( text_field );
            } else {
                $this.parent().next().find('input,select').show();
                $this.parent().next().find('input,select').replaceWith( response.data );
            }
        });

        return false;
    });

    // Update tax rate state field based on selected rate country
    $( document.body ).on('change', '#wpuf_tax_rates select.wpuf-tax-country', function() {
        var $this = $(this);
        var data = {
            action: 'wpuf_get_shop_states',
            country: $(this).val(),
            field_name: $this.attr('name').replace('country', 'state')
        };
        $.post(ajaxurl, data, function (response) {
            if( 'nostates' == response ) {
                var text_field = '<input type="text" name="' + data.field_name + '" value=""/>';
                $this.parent().next().find('select').replaceWith( text_field );
            } else {
                $this.parent().next().find('input,select').show();
                $this.parent().next().find('input,select').replaceWith( response );
            }
        });

        return false;
    });

    // Insert new tax rate row
    $('#wpuf_add_tax_rate').on('click', function() {
        var row = $('#wpuf_tax_rates tr:last');
        var clone = row.clone();
        var count = row.parent().find( 'tr' ).length;
        clone.find( 'td input' ).not(':input[type=checkbox]').val( '' );
        clone.find( 'td [type="checkbox"]' ).attr('checked', false);
        clone.find( 'input, select' ).each(function() {
            var name = $( this ).attr( 'name' );
            name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');
            $( this ).attr( 'name', name ).attr( 'id', name );
        });
        clone.find( 'label' ).each(function() {
            var name = $( this ).attr( 'for' );
            name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');
            $( this ).attr( 'for', name );
        });
        clone.insertAfter( row );
        return false;
    });

    // Remove tax row
    $( document.body ).on('click', '#wpuf_tax_rates .wpuf_remove_tax_rate', function() {
        var tax_rates = $('#wpuf_tax_rates tr:visible');
        var count     = tax_rates.length;

        if( count === 2 ) {
            $('#wpuf_tax_rates select').val('');
            $('#wpuf_tax_rates input[type="text"]').val('');
            $('#wpuf_tax_rates input[type="number"]').val('');
            $('#wpuf_tax_rates input[type="checkbox"]').attr('checked', false);
        } else {
            $(this).closest('tr').remove();
        }

        /* re-index after deleting */
        $('#wpuf_tax_rates tr').each( function( rowIndex ) {
            $(this).children().find( 'input, select' ).each(function() {
                var name = $( this ).attr( 'name' );
                name = name.replace( /\[(\d+)\]/, '[' + ( rowIndex - 1 ) + ']');
                $( this ).attr( 'name', name ).attr( 'id', name );
            });
        });
    });

})(jQuery);