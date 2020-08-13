<?php

if( isset( $_POST['insert_token_id'] ) && wp_verify_nonce( $_POST['qr_code_nonce'], 'wpuf_qr_code_token_id' ) ) {
    $error = '';

    if ( empty( $_POST['wpuf_qr_token_id'] ) ) {
        $error = __( 'Please insert your API key first', 'wpuf-pro' );
    }

    if ( !$error ) {
        update_option( 'wpuf_qr_code_token_id', $_POST['wpuf_qr_token_id'] );
        $success = __( 'Successfully Inserted your Token ID', 'wpuf-pro' );
    }

}

if( isset( $_POST['remove_token'] ) && wp_verify_nonce( $_POST['qr_code_remove_nonce'], 'wpuf_qr_code_token_remove_nonce' ) ) {
    delete_option( 'wpuf_qr_code_token_id' );
    $success = __( 'Successfully removed your API key', 'wpuf-pro' );
}


if( isset( $_POST['wpuf_save_default_options'] ) && wp_verify_nonce( $_POST['wpuf_save_option_nonce'], 'wpuf_save_options_field' ) ) {
     $wpuf_qr_settings = $_POST['wpuf_qr_settings'];

     update_option( 'wpuf_qr_settings', $wpuf_qr_settings );
     $success = __( "Settins Upadeted", 'wpuf-pro' );
}

?>

<?php if ( $error ): ?>
    <div class="error">
        <p><?php echo $error; ?></p>
    </div>
<?php endif ?>

<?php if ( $success ): ?>
    <div class="updated">
        <p><?php echo $success; ?></p>
    </div>
<?php endif ?>

<?php

$wpuf_user_settings = get_option('wpuf_qr_settings');

 ?>

<div class="postbox">
    <h3 style="padding:10px 15px"><?php _e( 'User Frontend Qr Code','wpuf-pro' ); ?></h3>
    <div class="inside wpuf_qr_code_wrapper">
        <div class="main">
        <?php if ( get_option( 'wpuf_qr_code_token_id' ) ): ?>

            <p id="wpuf_qr_code_token"><?php _e('Your Qr Code Token ID is :','wpuf-pro'); ?> <?php echo get_option( 'wpuf_qr_code_token_id' ); ?></p>

            <form action="" method="post" id="wpuf_qr-code_token_remove_action">
                <?php wp_nonce_field( 'wpuf_qr_code_token_remove_nonce', 'qr_code_remove_nonce' ); ?>
                <input type="submit" name="remove_token" class="button remove_token_class" value="<?php _e('Remove','wpuf-pro'); ?>" onclick="return removeApiConfirm()">
            </form>

        <?php else: ?>
            <form action="" method="post" style="margin-top: 20px;" id="wpuf_qr_code_token_form">
                <p class="help">Please insert your Correct Token Id Here</p>
                <input type="text" name="wpuf_qr_token_id" value="" class="wpuf_qr_token_id" size="50">
                <?php wp_nonce_field( 'wpuf_qr_code_token_id', 'qr_code_nonce' ); ?>
                <input type="submit" class="button button-primary" name="insert_token_id" value="<?php _e( 'Insert','wpuf-pro' ) ?>">
            </form>

        <?php endif ?>

        </div>
        <div class="clearfix"></div>
    </div>
</div>