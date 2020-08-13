<?php
// If MailPoet 3 is active, add as a provider.
 if( is_plugin_active( 'mailpoet/mailpoet.php' ) ) {
    global $post;

    $form_settings   = get_post_meta( $post->ID, 'wpuf_form_settings', true );
    
    $enable_mailpoet = isset( $form_settings['enable_mailpoet_3'] ) ? $form_settings['enable_mailpoet_3'] : 'no';
    $list_selected   = isset( $form_settings['mailpoet_3_list'] ) ? $form_settings['mailpoet_3_list'] : '';

    ?>

    <table class="form-table">

        <tr class="wpuf-post-type">
            <th><?php _e( 'Enable Mailpoet 3', 'wpuf-pro' ); ?></th>
            <td>
                <input type="checkbox" id="enable_mailpoet_3" name="wpuf_settings[enable_mailpoet_3]" value="yes" <?php echo ($enable_mailpoet=='yes') ? 'checked': '' ?> > <label for="enable_mailpoet_3"><?php  _e( 'Enable Mailpoet', 'wpuf-pro' ) ?></label>
            </td>
        </tr>

        <tr class="wpuf-redirect-to <?php echo ($enable_mailpoet=='yes') ? '': 'wpuf-hide' ?>">
            <th><?php _e( 'Select Preferred List', 'wpuf-pro' ); ?></th>
            <td>
                <?php
                $subscription_lists = \MailPoet\API\API::MP('v1')->getLists();
                ?>
                <?php if ( $subscription_lists ): ?>
                    <select name="wpuf_settings[mailpoet_3_list]">
                        <?php
                        foreach ( $subscription_lists as $list ) : ?>
                            <?php printf('<option value="%s"%s>%s</option>', $list['id'], selected( $list_selected, $list['id'], false ), $list['name'] ); ?>
                        <?php
                        endforeach;
                        ?>
                    </select>
                <?php endif; ?>
                <div class="description">
                    <?php _e( 'Select your mailpoet list for subscriptions', 'wpuf-pro' ) ?>
                </div>
            </td>
        </tr>
    </table>
<?php
} else {
    echo sprintf( '<div style="margin: 15px 0 10px;"><p><strong>%s</strong></p></div>', __( 'You need to install and activate the <a href="https://wordpress.org/plugins/mailpoet/" target="_blank">MailPoet</a> plugin for this option to be available.', 'wpuf-pro' ) );
}
