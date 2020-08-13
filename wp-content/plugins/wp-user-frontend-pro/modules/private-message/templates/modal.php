<div id="wpuf-form-template-modal">
    <div class="wpuf-form-template-modal">

        <!-- <span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.',  'wpuf-pro'  ); ?></span> -->
        <a href="#" class="close">× <!-- <span class="screen-reader-text"><?php _e( 'Close modal window',  'wpuf-pro'  ); ?></span> --></a>

        <header class="modal-header">
            <h2>
                <?php _e( 'Select User', 'wpuf-pro' ); ?>
            </h2>
        </header>

        <div class="content-container modal-footer">
            <div class="content">
                <div class="user-search"><input type="text" placeholder="<?php _e( 'Search User...',  'wpuf-pro'  ); ?>" name="search"></div>
                <?php $users = get_users( array() ); ?>
                <ul class="pm-user-list">
                    <?php foreach ($users as $user) { ?>
                        <li class="user">
                            <a href="<?php echo '#/user/'.$user->data->ID; ?>"><?php echo get_avatar($user->data->ID, 80). '<br>' .$user->data->user_login; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <footer>
            <?php _e( 'Select user to start chat.', 'wpuf-pro' ); ?>
        </footer>
    </div>
    <div class="wpuf-form-template-modal-backdrop"></div>
</div>


<script type="text/javascript">
(function($) {
    window.wpufpopup = {
        init: function() {
            $('.mailbox').on('click', 'button.new-message', this.openModal);
            $('.wpuf-form-template-modal-backdrop, .wpuf-form-template-modal .close').on('click', $.proxy(this.closeModal, this) );

            $('.content-container').on('keyup', '.user-search input', this.userSearch);

            $('body').on( 'keydown', $.proxy(this.onEscapeKey, this) );
        },

        openModal: function(e) {
            e.preventDefault();

            $('.wpuf-form-template-modal').show();
            $('.wpuf-form-template-modal-backdrop').show();
        },

        onEscapeKey: function(e) {
            if ( 27 === e.keyCode ) {
                this.closeModal(e);
            }
        },

        closeModal: function(e) {
            if ( typeof e !== 'undefined' ) {
                e.preventDefault();
            }

            $('.wpuf-form-template-modal').hide();
            $('.wpuf-form-template-modal-backdrop').hide();
        },

        userSearch: function function_name() {
            self = $(this);
            var data = {
                action: 'wpuf_pm_fetch_users',
                s: self.val(),
            };

            $.post( wpuf_frontend.ajaxurl, data, function( res ) {
                if ( res.success ) {
                    $('.pm-user-list' ).html( res.data.list );
                }
            });
        }
    };

    $(function() {
        wpufpopup.init();
    });

})(jQuery);
</script>
