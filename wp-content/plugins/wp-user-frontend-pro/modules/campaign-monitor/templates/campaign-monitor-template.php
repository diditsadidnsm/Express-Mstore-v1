<?php

if( isset( $_POST['connect_campaign_monitor'] ) && wp_verify_nonce( $_POST['campaign_monitor_nonce'], 'wpuf_campaign_monitor_api_key_nonce' ) ) {
    $error = '';

    if ( empty( $_POST['wpuf_campaign_monitor_api_key'] ) ) {
        $error = 'Please insert your API key first';
    }

    if ( !$error ) {
        update_option( 'wpuf_campaign_monitor_api_key', $_POST['wpuf_campaign_monitor_api_key'] );
        $success = __( 'Successfully added your API key', 'wpuf-pro' );
    } else {
        $error = $response['message'];
    }

}

if( isset( $_POST['remove_api'] ) && wp_verify_nonce( $_POST['campaign_monitor_remove_nonce'], 'wpuf_campaign_monitor_api_key_remove_nonce' ) ) {
    delete_option( 'wpuf_campaign_monitor_api_key' );
    delete_option( 'wpuf_camp_monitor_lists' );
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
    <div class="inside wpuf_camp_monitor_wrapper">
        <div class="main">
        <?php if ( get_option( 'wpuf_campaign_monitor_api_key' ) ): ?>

            <h3 id="wpuf_camp_monitor_api"><?php _e('Your Campaign Monitor API key is :','wpuf-pro'); ?> </h3> <?php echo get_option( 'wpuf_campaign_monitor_api_key' ); ?></p>

            <form action="" method="post" id="wpuf_camp_monitor_api_remove_action">
                <?php wp_nonce_field( 'wpuf_campaign_monitor_api_key_remove_nonce', 'campaign_monitor_remove_nonce' ); ?>
                <input type="submit" name="remove_api" class="button remove_api_class" value="<?php _e('Remove','wpuf-pro'); ?>" onclick="return removeApiConfirm()">
            </form>

        <?php else: ?>

            <h3 style="padding:10px 5px"><?php _e( 'Please insert your Campaign Monitor API key:','wpuf-pro'); ?></h3>

            <form action="" method="post" style="margin-top: 20px;" id="wpuf_campaign_monitor_api_form">
                <input type="text" style="width: 40%" name="wpuf_campaign_monitor_api_key" value="" class="wpuf_campaign_monitor_api">
                <?php wp_nonce_field( 'wpuf_campaign_monitor_api_key_nonce', 'campaign_monitor_nonce' ); ?>
                <input type="submit" class="button button-primary" name="connect_campaign_monitor" value="<?php _e( 'Connect','wpuf-pro') ?>">
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