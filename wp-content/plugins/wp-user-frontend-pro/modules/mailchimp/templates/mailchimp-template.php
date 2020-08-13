<?php

require_once dirname( __FILE__ ) . '/../includes/wpuf_mailchimp_functions.php';

if( isset( $_POST['connect_mailchimp'] ) && wp_verify_nonce( $_POST['mailchimp_nonce'], 'wpuf_mailchimp_api_key_nonce' ) ) {
    $error = '';

    if ( empty( $_POST['wpuf_mailchimp_api_key'] ) ) {
        $error = __( 'Please insert your API key first', 'wpuf-pro' );
    }

    if ( !$error ) {

        $response = save_mailchimp_api( $_POST['wpuf_mailchimp_api_key'] );

        if ( (bool)$response['status'] ) {
            update_option( 'wpuf_mailchimp_api_key', $_POST['wpuf_mailchimp_api_key'] );
            $success = $response['message'];
        } else {
            $error = $response['message'];
        }
    }

}

if( isset( $_POST['remove_api'] ) && wp_verify_nonce( $_POST['mailchimp_remove_nonce'], 'wpuf_mailchimp_api_key_remove_nonce' ) ) {
    delete_option( 'wpuf_mailchimp_api_key' );
    delete_option( 'wpuf_mc_lists' );
    $success = __( 'Successfully removed your API key', 'wpuf-pro' );
}

if( isset( $_POST['refresh_mailchimp_lists'] ) && wp_verify_nonce( $_POST['mailchimp_refresh_nonce'], 'wpuf_mailchimp_api_key_refresh_nonce' ) ) {
    refresh_mailchimp_api_lists();
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
    <h3 style="padding:10px 15px"><?php _e( 'Mailchimp','wpuf-pro' ); ?></h3>
    <div class="inside wpuf_mc_wrapper">
        <div class="main">
        <?php if ( get_option( 'wpuf_mailchimp_api_key' )): ?>

            <p id="wpuf_mc_api"><?php _e('Your Mailchimp API key is :','wpuf-pro'); ?> <?php echo get_option( 'wpuf_mailchimp_api_key' ); ?></p>

            <form action="" method="post" id="wpuf_mc_api_remove_action">
                <?php wp_nonce_field( 'wpuf_mailchimp_api_key_remove_nonce', 'mailchimp_remove_nonce' ); ?>
                <input type="submit" name="remove_api" class="button remove_api_class" value="<?php _e('Remove','wpuf-pro'); ?>" onclick="return removeApiConfirm()">
            </form>

            <form action="" method="post" id="wpuf_mc_api_refresh_action">
                <?php wp_nonce_field( 'wpuf_mailchimp_api_key_refresh_nonce', 'mailchimp_refresh_nonce' ); ?>
                <input type="submit" name="refresh_mailchimp_lists" class="button button-primary refresh_api_class" value="<?php _e('Refresh','wpuf-pro'); ?>">
            </form>

        <?php else: ?>

            <form action="" method="post" style="margin-top: 20px;" id="wpuf_mailchimp_api_form">
                <p class="help"><?php _e( 'Please insert your Mailchimp API key.','wpuf-pro' ) ?> <a target="_blank" href="http://admin.mailchimp.com/account/api"><?php _e( 'Get your API key here.', 'wpuf-pro' ); ?></a></p>
                <input type="text" name="wpuf_mailchimp_api_key" value="" class="wpuf_mailchimp_api">
                <?php wp_nonce_field( 'wpuf_mailchimp_api_key_nonce', 'mailchimp_nonce' ); ?>
                <input type="submit" class="button button-primary" name="connect_mailchimp" value="<?php _e( 'Connect','wpuf-pro' ) ?>">
            </form>

        <?php endif ?>

        </div>
    </div>
</div>


<table class="widefat meta" style="margin-top: 20px;">
    <thead>
        <tr>
            <th scope="col"><?php _e( 'SL. No.', 'wpuf-pro' ); ?></th>
            <th scope="col"><?php _e( 'List Name', 'wpuf-pro' ); ?></th>
        </tr>
    </thead>
    <tbody>

    <?php $lists = get_option( 'wpuf_mc_lists'); ?>

    <?php $i=1; if ( $lists ):?>
        <?php foreach ( $lists as $key => $value): ?>

            <tr valign="top" class="alternate">
                <td><?php echo $i; ?></td>
                <td>
                    <?php list(, $datacentre) = explode('-', get_option( 'wpuf_mailchimp_api_key' )); ?>
                    <a href="https://<?php echo $datacentre; ?>.admin.mailchimp.com/lists/members/?id=<?php echo $value['web_id']; ?>" target="_blank"><?php echo $value['name']; ?></a></td>
            </tr>

        <?php $i++; endforeach ?>
    <?php else: ?>
            <tr>
            <?php if ( get_option( 'wpuf_mailchimp_api_key' ) ): ?>
                <?php list(, $datacentre) = explode('-', get_option( 'wpuf_mailchimp_api_key' )); ?>
                <td>
                    <?php _e( 'No List Found. ', 'wpuf-pro' ) ?><a href="https://<?php echo $datacentre; ?>.admin.mailchimp.com/lists/" target="_blank"><?php _e( 'Create your list here', 'wpuf-pro' ) ?></a>
                </td>
            <?php else: ?>
                <td>
                    <?php _e( 'You are not connected with mailchimp. Insert your API key if not ', 'wpuf-pro' )  ?><a target="_blank" href="http://admin.mailchimp.com/account/api"><?php _e( 'Get your API key here.', 'wpuf-pro' ); ?></a>
                </td>
            <?php endif ?>

            </tr>
    <?php endif ?>

    </tbody>
</table>


<script>

    function removeApiConfirm() {
        var r = confirm("Are you sure want to delete?");
        if (r == false) {
            return false;
        }
    }
</script>