<?php
global $post;

$form_settings = get_post_meta( $post->ID, 'wpuf_form_settings', true );

$enable_zapier = isset( $form_settings['enable_zapier'] ) ? $form_settings['enable_zapier'] : 'no';
$webhook_url   = isset( $form_settings['zapier_webhook'] ) ? $form_settings['zapier_webhook'] : '';
?>

<table class="form-table">

    <tr class="wpuf-post-type">
        <th><?php _e( 'Enable Zapier', 'wpuf-pro' ); ?></th>
        <td>
            <input type="checkbox" id="enable_zapier" name="wpuf_settings[enable_zapier]" value="yes" <?php echo ($enable_zapier=='yes') ? 'checked': '' ?> > <label for="enable_zapier"><?php  _e( 'Enable Zapier', 'wpuf-pro' ) ?></label>
        </td>
    </tr>

    <tr class="wpuf-redirect-to">
        <th><?php _e( 'Zapier Webhook URL', 'wpuf-pro' ); ?></th>
        <td>
            <input type="url" class="text" id="zapier_webhook" name="wpuf_settings[zapier_webhook]" placeholder="https://hooks.zapier.com/hooks/catch/..." value="<?php echo $webhook_url ?>">
            <p class="help"><?php printf( __( 'Please provide your %sZapier Webhook URL%s here', 'wpuf-pro' ), '<a href="https://zapier.com/app/dashboard" target="_blank" >', '</a>' ); ?></p>
        </td>
</table>
