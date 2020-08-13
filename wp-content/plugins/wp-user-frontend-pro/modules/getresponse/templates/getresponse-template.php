<?php

if( isset( $_POST['connect_getresponse'] ) && wp_verify_nonce( $_POST['getresponse_nonce'], 'wpuf_getresponse_api_key_nonce' ) ) {
    $error = '';

    if ( empty( $_POST['wpuf_getresponse_api_key'] ) ) {
        $error = __( 'Please insert your API key first','wpuf-pro' );
    }

    if ( !$error ) {
        update_option( 'wpuf_getresponse_api_key', $_POST['wpuf_getresponse_api_key'] );
        $success = __( 'Successfully added your API key', 'wpuf-pro' );
    } else {
        $error = $response['message'];
    }

}

if( isset( $_POST['remove_api'] ) && wp_verify_nonce( $_POST['getresponse_remove_nonce'], 'wpuf_getresponse_api_key_remove_nonce' ) ) {
    delete_option( 'wpuf_getresponse_api_key' );
    delete_option( 'wpuf_gr_lists' );
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
    <div class="inside wpuf_gr_wrapper">
        <div class="main">
        <?php if ( get_option( 'wpuf_getresponse_api_key' ) ): ?>

            <h3 id="wpuf_gr_api"><?php _e('Your GetResponse API key is :','wpuf-pro' ); ?> </h3> <?php echo get_option( 'wpuf_getresponse_api_key' ); ?>

            <form action="" method="post" id="wpuf_gr_api_remove_action">
                <?php wp_nonce_field( 'wpuf_getresponse_api_key_remove_nonce', 'getresponse_remove_nonce' ); ?>
                <input type="submit" name="remove_api" class="button remove_api_class" value="<?php _e('Remove','wpuf-pro' ); ?>" onclick="return removeApiConfirm()">
            </form>

        <?php else: ?>

            <h3 style="padding:10px 5px"><?php _e( 'Please insert your GetResponse API key:','wpuf-pro' ); ?></h3>
            <form action="" method="post" style="margin-top: 20px;" id="wpuf_getresponse_api_form">
                <input type="text" style="width: 40%" name="wpuf_getresponse_api_key" value="" class="wpuf_getresponse_api">
                <?php wp_nonce_field( 'wpuf_getresponse_api_key_nonce', 'getresponse_nonce' ); ?>
                <input type="submit" class="button button-primary" name="connect_getresponse" value="<?php _e( 'Connect','wpuf-pro' ) ?>">
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