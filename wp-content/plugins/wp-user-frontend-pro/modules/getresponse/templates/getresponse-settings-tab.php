<?php
global $post;

$form_settings       = get_post_meta( $post->ID, 'wpuf_form_settings', true );

$enable_getresponse  = isset( $form_settings['enable_getresponse'] ) ? $form_settings['enable_getresponse'] : 'no';
$list_selected       = isset( $form_settings['getresponse_list'] ) ? $form_settings['getresponse_list'] : '';
?>

<table class="form-table">

    <tr class="wpuf-post-type">
        <th><?php _e( 'Enable GetResponse', 'wpuf-pro' ); ?></th>
        <td>
            <input type="checkbox" id="enable_getresponse" name="wpuf_settings[enable_getresponse]" value="yes" <?php echo ($enable_getresponse=='yes') ? 'checked': '' ?> > <label for="enable_getresponse"><?php  _e( 'Enable GetResponse', 'wpuf-pro' ) ?></label>
        </td>
    </tr>

    <tr class="wpuf-redirect-to">
        <th><?php _e( 'Select Preferred List', 'wpuf-pro' ); ?></th>
        <td>
            <?php $lists = get_option( 'wpuf_gr_lists');
                if ( $lists ) { ?>
                <select name="wpuf_settings[getresponse_list]">
                    <?php foreach ( $lists as $key => $value) {
                        printf('<option value="%s"%s>%s</option>', $value['id'], selected( $list_selected, $value['id'], false ), $value['name'] );
                    } ?>
                </select>

                <div class="description">
                    <?php _e( 'Select your getresponse list for subscriptions', 'wpuf-pro' ) ?>
                </div>

            <?php } else {
                    _e( 'You are not connected with getresponse. Insert your API key first', 'wpuf-pro' );
            } ?>
        </td>
    </tr>
</table>
