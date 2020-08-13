<?php
    global $post;

    $image_url     = WPUF_QR_DIR. '/images/';
    $form_settings = wpuf_get_form_settings( $post->ID );
    $alt_text      = isset( $form_settings['alt_text'] ) ? $form_settings['alt_text'] : 'QR Code';
    $size          = isset( $form_settings['size'] ) ? $form_settings['size'] : 200;
    $qrcolor       = isset( $form_settings['qrcolor'] ) ? $form_settings['qrcolor'] : '000000';
?>

<div id="wpuf-metabox-qr-code" class="group">
    <div class="align_left">
        <h2><?php _e( 'Settings', 'wpuf-pro' ); ?></h2>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="wpuf_settings[alt_text]">
                            <?php _e( 'Default alternative text', 'wpuf-pro' ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="wpuf_settings[alt_text]" value="<?php echo esc_attr( $alt_text ); ?>" id="wpuf_settings[alt_text]" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpuf_settings[size]">
                            <?php _e( 'Default size (max. 500px)', 'wpuf-pro' ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="wpuf_settings[size]" value="<?php echo esc_attr( $size ); ?>" id="wpuf_settings[size]" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpuf_settings[qrcolor]">
                            <?php _e( 'Choose color', 'wpuf-pro' ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="color" name="wpuf_settings[qrcolor]" value="<?php echo esc_attr( $qrcolor ); ?>" id="wpuf_settings[qrcolor]" />
                    </td>
                </tr>
            </table>

            <br/>

        </form>
    </div>
    <div class="clearfix"></div>
</div>
