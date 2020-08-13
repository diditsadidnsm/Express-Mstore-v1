;(function($){

    var WPUF_Admin = {

        init: function() {
            $('.wpuf-modules').on( 'change', 'input.wpuf-toggle-module', this.toggleModule );
            $('#activate-all-modules, #deactivate-all-modules').on( 'click', this.toggleAllModules );
        },

        toggleModule: function(e) {
            var self = $(this);

            if ( self.is(':checked') ) {
                // Enabled
                var mesg = wpuf_module.activating,
                    data = {
                        action: 'wpuf-toggle-module',
                        type: 'activate',
                        module: self.closest( 'li' ).data( 'module' ),
                        nonce: wpuf_module.nonce
                    };
            } else {
                // Disbaled
                var mesg = wpuf_module.deactivating,
                    data = {
                        action: 'wpuf-toggle-module',
                        type: 'deactivate',
                        module: self.closest( 'li' ).data( 'module' ),
                        nonce: wpuf_module.nonce
                    };
            }

            self.closest('.plugin-card').block({
                message: mesg,
                overlayCSS: { background: '#222', opacity: 0.7 },
                css: {
                    fontSize: '19px',
                    color:      '#fff',
                    border:     'none',
                    backgroundColor:'none',
                    cursor:     'wait'
                },
            });

            wp.ajax.send( 'wpuf-toggle-module', {
                data: data,
                success: function(response) {

                },

                error: function(error) {
                    if ( error.error === 'plugin-exists' ) {
                        wp.ajax.send( 'wpuf-toggle-module', {
                            data: data
                        });
                    }
                },

                complete: function(resp) {
                    $('.blockMsg').text(resp.data);
                    setTimeout( function() {
                        self.closest('.plugin-card').unblock();
                    }, 1000)
                }
            });
        },

        toggleAllModules: function(e) {
            if ( e.target.id === 'activate-all-modules' ) {
                var mesg = wpuf_module.activating,
                data = {
                    action: 'wpuf-toggle-all-modules',
                    type: 'activate',
                    nonce: wpuf_module.nonce
                };
            } else {
                var mesg = wpuf_module.deactivating,
                data = {
                    action: 'wpuf-toggle-all-modules',
                    type: 'deactivate',
                    nonce: wpuf_module.nonce
                };
            }

            $('.plugin-card').block({
                message: mesg,
                overlayCSS: { background: '#222', opacity: 0.7 },
                css: {
                    fontSize: '19px',
                    color:      '#fff',
                    border:     'none',
                    backgroundColor:'none',
                    cursor:     'wait'
                },
            });

            wp.ajax.send( 'wpuf-toggle-all-modules', {
                data: data,
                success: function(response) {},

                complete: function(resp) {
                    setTimeout( function() {
                        $('.plugin-card').unblock();
                    }, 1000);
                    window.location.reload(true);
                }
            });
        }
    };

    $(document).ready(function(){
        WPUF_Admin.init();
    });
})(jQuery);