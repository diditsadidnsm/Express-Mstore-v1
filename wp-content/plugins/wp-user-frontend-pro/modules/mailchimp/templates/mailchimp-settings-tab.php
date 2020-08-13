<?php
global $post;

$form_settings       = get_post_meta( $post->ID, 'wpuf_form_settings', true );
$form_fields         = wpuf_get_form_fields( $post->ID);

$enable_mailchimp    = isset( $form_settings['enable_mailchimp'] ) ? $form_settings['enable_mailchimp'] : 'no';
$list_selected       = isset( $form_settings['mailchimp_list'] ) ? $form_settings['mailchimp_list'] : '';
$enable_double_optin = isset( $form_settings['enable_double_optin'] ) ? $form_settings['enable_double_optin'] : 'no';
$conditional_logic   = isset( $form_settings['integrations']['mailchimp']['wpuf_cond']['condition_status'] ) ? $form_settings['integrations']['mailchimp']['wpuf_cond']['condition_status'] : 'no';
$condition_operator  = isset( $form_settings['integrations']['mailchimp']['wpuf_cond']['conditions']['operator'] ) ? $form_settings['integrations']['mailchimp']['wpuf_cond']['conditions']['operator'] : '=';
$condition_name      = isset( $form_settings['integrations']['mailchimp']['wpuf_cond']['conditions']['name'] ) ? $form_settings['integrations']['mailchimp']['wpuf_cond']['conditions']['name'] : '';
$condition_option    = isset( $form_settings['integrations']['mailchimp']['wpuf_cond']['conditions']['option'] ) ? $form_settings['integrations']['mailchimp']['wpuf_cond']['conditions']['option'] : '';

if ( !empty( $condition_name ) ) {
    foreach ( $form_fields as $field ) {
        if ( $field['name'] == $condition_name ) {
            $condition_field_label = $field['label'];

            if ( !empty( $condition_option ) ) {
                $condition_option_label = $field['options'][$condition_option];
            }
        }
    }
}
?>

<table class="form-table">

    <tr class="wpuf-post-type">
        <th><?php _e( 'Enable Mailchimp', 'wpuf-pro' ); ?></th>
        <td>
            <input type="checkbox" id="enable_mailchimp" name="wpuf_settings[enable_mailchimp]" value="yes" <?php echo ($enable_mailchimp=='yes') ? 'checked': '' ?> > <label for="enable_mailchimp"><?php  _e( 'Enable Mailchimp', 'wpuf-pro' ) ?></label>
        </td>
    </tr>

    <tr class="wpuf-redirect-to">
        <th><?php _e( 'Select Preferred List', 'wpuf-pro' ); ?></th>
        <td>
            <?php $lists = get_option( 'wpuf_mc_lists');

                if ( $lists ) { ?>
                <select name="wpuf_settings[mailchimp_list]">
                    <?php foreach ( $lists as $key => $value) {
                        printf('<option value="%s"%s>%s</option>', $value['id'], selected( $list_selected, $value['id'], false ), $value['name'] );
                    } ?>
                </select>

                <div class="description">
                    <?php _e( 'Select your mailchimp list for subscriptions', 'wpuf-pro' ) ?>
                </div>

            <?php } else {
                if ( get_option( 'wpuf_mailchimp_api_key' ) ) {
                    list(, $datacentre) = explode('-', get_option( 'wpuf_mailchimp_api_key' ));
                    printf( '%s <a href="https://%s.admin.mailchimp.com/lists/" target="_blank">%s</a>', __( 'No List Found. ', 'wpuf-pro' ), $datacentre, __( 'Create your list here', 'wpuf-pro' ) );
                } else {
                    _e( 'You are not connected with mailchimp. Insert your API key first', 'wpuf-pro' );
                }
            } ?>
        </td>
    </tr>

    <tr class="wpuf-post-type">
        <th><?php _e( 'Double Optin', 'wpuf-pro' ); ?></th>
        <td>
            <input type="checkbox" id="enable_double_optin" name="wpuf_settings[enable_double_optin]" value="yes" <?php echo ($enable_double_optin=='yes') ? 'checked': '' ?> > <label for="enable_double_optin"><?php _e( 'Enable Double Optin', 'wpuf-pro' ); ?></label>
        </td>
    </tr>

    <tr>
        <th><?php _e( 'Conditional Logic', 'wpuf-pro' ); ?></th>
        <td>
            <input type="hidden" name="'wpuf_settings[integrations]['mailchimp'][wpuf_cond][condition_status]'" value="no">
            <label><input type="radio" value="yes" name="wpuf_settings[integrations][mailchimp][wpuf_cond][condition_status]" class="wpuf-integrations-conditional-logic" <?php checked( $conditional_logic, 'yes' ); ?>> <?php _e( 'Yes', 'wpuf-pro' ); ?></label>
            <label><input type="radio" value="no" name="wpuf_settings[integrations][mailchimp][wpuf_cond][condition_status]"  class="wpuf-integrations-conditional-logic" <?php checked( $conditional_logic, 'no' ); ?>> <?php _e( 'No', 'wpuf-pro' ); ?></label>
        </td>
    </tr>

    <tr class="wpuf-integrations-conditional-logic-container <?php echo ( $conditional_logic == 'no' ) ? 'hidden-field' : ''; ?>">
        <th></th>
        <td>
            <div class="cond-field" style="display:inline-block">
                <select name="wpuf_settings[integrations][mailchimp][wpuf_cond][conditions][name]" class="wpuf_available_conditional_fields">
                    <option value="<?php echo !empty( $condition_name ) ? $condition_name : 'select';  ?>" selected><?php echo !empty( $condition_field_label ) ? $condition_field_label : '- select -';  ?></option>
                </select>
            </div>

            <div class="cond-operator" style="display:inline-block">
                <select name="wpuf_settings[integrations][mailchimp][wpuf_cond][conditions][operator]">
                    <option value="=" <?php selected( $condition_operator, '=', true); ?>><?php _e( 'is', 'wpuf-pro' ); ?></option>
                    <option value="!=" <?php selected( $condition_operator, '!=', true); ?>><?php _e( 'is not', 'wpuf-pro' ); ?></option>
                </select>
            </div>

            <div class="cond-option" style="display:inline-block">
                <select name="wpuf_settings[integrations][mailchimp][wpuf_cond][conditions][option]" class="wpuf_selected_conditional_field_options">
                    <option value="<?php echo !empty( $condition_option ) ? $condition_option : 'select';  ?>" selected><?php echo !empty( $condition_option_label ) ? $condition_option_label : '- select -';  ?></option>
                </select>
            </div>
            <div class="description">
                <?php _e( 'Mailchimp integration will run if the above condition meets.', 'wpuf-pro' ) ?>
            </div>
        </td>
    </tr>
</table>