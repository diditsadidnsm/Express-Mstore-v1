;(function($) {

    var wpufQrCode = {

        init: function() {

            $('.qr_code_wrap').on('change','.qr_code_type_class', this.sendAjaxFormField);

            $('.qr_code_type_class').each(function(i, obj) {

                 var type = $(this).val();
                 $(this).val(type).trigger('change');
            
            });
        },

        sendAjaxFormField: function() {

            var self = $(this),
                data = {
                    action: 'build_qr_type_field',
                    formfield : self.data('formfield'),
                    postid : self.data('postid'),
                    type : self.val(),
                    posttype: self.data('posttype'),
                };

            $.post( wpufqrcode.ajaxurl, data, function(res) {

                if( res.success ) {
                    self.closest('.qr_code_wrap').find('.apppend_data').html(res.data.appned_data);
                }
                               
            });
        }
    }

    wpufQrCode.init();

})(jQuery);