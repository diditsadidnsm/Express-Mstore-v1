<?php
global $post;

$form_settings           = get_post_meta( $post->ID, 'wpuf_form_settings', true );

$enable_campaign_monitor = isset( $form_settings['enable_campaign_monitor'] ) ? $form_settings['enable_campaign_monitor'] : 'no';
$list_selected           = isset( $form_settings['campaign_monitor_list'] ) ? $form_settings['campaign_monitor_list'] : '';
?>

<table class="form-table">
    <tr class="wpuf-post-type">
        <th><?php _e( 'Enable Campaign Monitor', 'wpuf-pro' ); ?></th>
        <td>
            <input type="checkbox" id="enable_campaign_monitor" name="wpuf_settings[enable_campaign_monitor]" value="yes" <?php echo ($enable_campaign_monitor=='yes') ? 'checked': '' ?> > <label for="enable_campaign_monitor"><?php  _e( 'Enable Campaign Monitor', 'wpuf-pro' ) ?></label>
        </td>
    </tr>

    <tr class="wpuf-redirect-to">
        <th><?php _e( 'Select Preferred List', 'wpuf-pro'); ?></th>
        <td>
            <?php $lists = get_option( 'wpuf_camp_monitor_lists');
                if ( $lists ) { ?>
                <select name="wpuf_settings[campaign_monitor_list]">
                    <?php foreach ( $lists as $key => $value) {
                        printf( '<option value="%s"%s>%s</option>', $value['id'], selected( $list_selected, $value['id'], false ), $value['name'] );
                    } ?>
                </select>

                <div class="description">
                    <?php _e( 'Select your campaign_monitor list for subscriptions', 'wpuf-pro' ) ?>
                </div>

            <?php } else {
                    _e( 'You are not connected with campaign_monitor. Insert your API key first', 'wpuf-pro' );
            } ?>
        </td>
    </tr>
</table>
