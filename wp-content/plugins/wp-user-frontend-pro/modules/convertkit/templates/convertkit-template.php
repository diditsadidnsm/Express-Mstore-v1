<?php

if( isset( $_POST['connect_convertkit'] ) && wp_verify_nonce( $_POST['convertkit_nonce'], 'wpuf_convertkit_api_key_nonce' ) ) {
    $error = '';

    if ( empty( $_POST['wpuf_convertkit_api_key'] ) ) {
        $error = __( 'Please insert your API key & Secret key first', 'wpuf-pro' );
    }

    if ( !$error ) {
        update_option( 'wpuf_convertkit_api_key', $_POST['wpuf_convertkit_api_key'] );
        update_option( 'wpuf_convertkit_secret_key', $_POST['wpuf_convertkit_secret_key'] );
        update_option( 'wpuf_convertkit_double_opt', $_POST['wpuf_convertkit_double_opt'] );
        $success = __( 'Successfully added your API key & Secret key', 'wpuf-pro' );
    } else {
        $error = $response['message'];
    }

}

if( isset( $_POST['remove_api'] ) && wp_verify_nonce( $_POST['convertkit_remove_nonce'], 'wpuf_convertkit_api_key_remove_nonce' ) ) {
    delete_option( 'wpuf_convertkit_api_key' );
    delete_option( 'wpuf_convertkit_secret_key' );
    delete_option( 'wpuf_convertkit_double_opt' );
    delete_option( 'wpuf_ck_lists' );
    $success = __( 'Successfully removed your API key', 'wpuf-pro' );
}

?>

<?php if ( ! empty( $error ) ): ?>
    <div class="error">
        <p><?php echo $error; ?></p>
    </div>
<?php endif ?>

<?php if ( ! empty( $success ) ): ?>
    <div class="updated">
        <p><?php echo $success; ?></p>
    </div>
<?php endif ?>

<div class="postbox">

    <div class="inside wpuf_ck_wrapper">
        <div class="main">
        <?php if ( get_option( 'wpuf_convertkit_api_key' ) && get_option( 'wpuf_convertkit_secret_key' ) ): ?>

            <h3 id="wpuf_ck_api"><?php _e('Your ConvertKit API key is :','wpuf-pro'); ?> </h3> <?php echo get_option( 'wpuf_convertkit_api_key' ); ?>
            <h3 id="wpuf_ck_secret"><?php _e('Your ConvertKit Secret key is :','wpuf-pro'); ?> </h3> <?php echo get_option( 'wpuf_convertkit_secret_key' ); ?>

            <form action="" method="post" id="wpuf_ck_api_remove_action">
                <?php wp_nonce_field( 'wpuf_convertkit_api_key_remove_nonce', 'convertkit_remove_nonce' ); ?>
                <input type="submit" name="remove_api" class="button remove_api_class" value="<?php _e( 'Remove','wpuf-pro' ); ?>" onclick="return removeApiConfirm()">
            </form>

        <?php else: ?>

            <h3 style="padding:10px 5px"><?php _e( 'Please insert your ConvertKit API key & Secret Key:','wpuf-pro' ); ?></h3>

            <form action="" method="post" style="margin-top: 20px;" id="wpuf_convertkit_api_form">
                <label>API Key</label>
                <p><input type="text" style="width: 40%" name="wpuf_convertkit_api_key" value="" class="wpuf_convertkit_api"></p>
                <label>Secret Key</label>
                <p><input type="text" style="width: 40%" name="wpuf_convertkit_secret_key" value="" class="wpuf_convertkit_api"></p>
                <input type="hidden" name="wpuf_convertkit_double_opt" value="false">
            </p>
                <?php wp_nonce_field( 'wpuf_convertkit_api_key_nonce', 'convertkit_nonce' ); ?>
                <input type="submit" class="button button-primary" name="connect_convertkit" value="<?php _e( 'Connect','wpuf-pro' ) ?>">
            </form>

        <?php endif ?>

        </div>
    </div>
</div>


<script>

    function removeApiConfirm() {
        var r = confirm("Are you sure want to delete?");
        if (r == false) {
            return false;
        }
    }
</script>
