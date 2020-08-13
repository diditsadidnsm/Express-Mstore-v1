<?php
/**
 * Repeat Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Repeat extends WPUF_Field_Contract {

    use WPUF_Form_Field_Post_trait;

    function __construct() {
        $this->name       = __( 'Repeat Field', 'wpuf-pro' );
        $this->input_type = 'repeat_field';
        $this->icon       = 'clone';
    }

    /**
     * Render the Repeat field
     *
     * @param  array  $field_settings
     *
     * @param  integer  $form_id
     *
     * @param  string  $type
     *
     * @param  integer  $post_id
     *
     * @return void
     */
    public function render( $field_settings, $form_id, $type = 'post', $post_id = null ) {

        $add    = plugins_url( 'assets/images/add.png', WPUF_FILE );
        $remove = plugins_url( 'assets/images/remove.png', WPUF_FILE );

        $this->field_print_label( $field_settings, $form_id );

        ?>

        <div class="wpuf-fields <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>">

        <?php if ( isset( $field_settings['multiple'] ) && '' != $field_settings['multiple'] ) { ?>
            <table>
                <thead>
                    <tr>
                        <?php
                        $num_columns = count( $field_settings['columns'] );

                        foreach ( $field_settings['columns'] as $column) { ?>
                            <th> <?php echo $column; ?> </th>
                        <?php } ?>
                            <th style="visibility: hidden;"> Actions </th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                        $items = $post_id ? $this->get_meta( $post_id, $field_settings['name'], $type, false ) : array();

                        if ( $items ) {
                            foreach ($items as $item_val) {
                                $column_vals = explode( "|", $item_val );
                            ?>

                            <tr>
                                <?php for ( $count = 0; $count < $num_columns; $count++ ) { ?>
                                    <td>
                                        <input type="text" name="<?php echo $field_settings['name'] . '[' . $count . ']'; ?>[]" value="<?php echo esc_attr( trim( $column_vals[$count] ) ); ?>" size="<?php echo esc_attr( $field_settings['size'] ) ?>" data-required="<?php echo $field_settings['required'] ?>" data-type="text"
                                        <?php $this->required_html5( $field_settings ); ?>
                                    />
                                    </td>
                                <?php } ?>
                                <td>
                                    <img class="wpuf-clone-field" alt="<?php esc_attr_e( 'Add another', 'wpuf-pro' ); ?>" title="<?php esc_attr_e( 'Add another', 'wpuf-pro' ); ?>" src="<?php echo $add; ?>">
                                    <img class="wpuf-remove-field" alt="<?php esc_attr_e( 'Remove this choice', 'wpuf-pro' ); ?>" title="<?php esc_attr_e( 'Remove this choice', 'wpuf-pro' ); ?>" src="<?php echo $remove; ?>">
                                </td>
                            </tr>

                        <?php }  // endforeach ?>

                    <?php } else { ?>

                        <tr>
                            <?php for ( $count = 0; $count < $num_columns; $count++ ) { ?>
                                <td>
                                    <input type="text" name="<?php echo $field_settings['name'] . '[' . $count . ']'; ?>[]" size="<?php echo esc_attr( $field_settings['size'] ) ?>" data-required="<?php echo $field_settings['required'] ?>" data-type="text"
                                    <?php $this->required_html5( $field_settings ); ?>
                                />
                                </td>
                            <?php } ?>
                            <td>
                                <img class="wpuf-clone-field" alt="<?php esc_attr_e( 'Add another', 'wpuf-pro' ); ?>" title="<?php esc_attr_e( 'Add another', 'wpuf-pro' ); ?>" src="<?php echo $add; ?>">
                                <img class="wpuf-remove-field" alt="<?php esc_attr_e( 'Remove this choice', 'wpuf-pro' ); ?>" title="<?php esc_attr_e( 'Remove this choice', 'wpuf-pro' ); ?>" src="<?php echo $remove; ?>">
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>

        <?php } else {   ?>

            <table>
                <?php

                if ( isset( $post_id ) &&  $post_id != '0' ) {
                    $items = $post_id ? explode("|", $this->get_meta( $post_id, $field_settings['name'], $type, true ) ) : array();
                }

                if ( isset( $items ) ) {
                    foreach ( $items as $item ) { ?>

                        <tr>
                            <td>
                                <input
                                type="text"
                                data-required="<?php echo $field_settings['required'] ?>"
                                data-type="text"<?php $this->required_html5( $field_settings ); ?>
                                name="<?php echo esc_attr( $field_settings['name'] ); ?>[]"
                                placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>"
                                value="<?php echo esc_attr( $item ) ?>"
                                size="<?php echo esc_attr( $field_settings['size'] ) ?>" />
                            </td>
                            <td>
                                <img style="cursor:pointer; margin:0 3px;" alt="add another choice" title="add another choice" class="wpuf-clone-field" src="<?php echo $add; ?>">
                                <img style="cursor:pointer;" class="wpuf-remove-field" alt="remove this choice" title="remove this choice" src="<?php echo $remove; ?>">
                            </td>
                        </tr>

                    <?php } //endforeach ?>

                <?php } else { ?>

                    <tr>
                        <td>
                            <input  type="text" data-required="<?php echo $field_settings['required'] ?>" data-type="text" name="<?php echo esc_attr( $field_settings['name'] ); ?>[]" placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>" value="<?php echo esc_attr( $field_settings['default'] ) ?>" size="<?php echo esc_attr( $field_settings['size'] ) ?>" />
                        </td>
                        <td>
                            <img style="cursor:pointer; margin:0 3px;" alt="add another choice" title="add another choice" class="wpuf-clone-field" src="<?php echo $add; ?>">
                            <img style="cursor:pointer;" class="wpuf-remove-field" alt="remove this choice" title="remove this choice" src="<?php echo $remove; ?>">
                        </td>
                    </tr>

                <?php } ?>

            </table>

        <?php } ?>

        <?php $this->help_text( $field_settings ); ?>
        </div>

        <?php $this->after_field_print_label();
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings( true, array( 'width' ) );

        $settings = array(
            array(
                'name'          => 'multiple',
                'title'         => __( 'Multiple Column', 'wpuf-pro' ),
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => array(
                    'true'   => __( 'Enable Multi Column', 'wpuf-pro' )
                ),
                'section'       => 'advanced',
                'priority'      => 23,
                'help_text'     => '',
            ),

            array(
                'name'          => 'columns',
                'title'         => __( 'Columns', 'wpuf-pro' ),
                'type'          => 'repeater-columns',
                'section'       => 'advanced',
                'priority'      => 24,
                'help_text'     => '',
                'dependencies' => array(
                    'multiple' => 'true'
                )
            ),

            array(
                'name'         => 'placeholder',
                'title'        => __( 'Placeholder text', 'wpuf-pro' ),
                'type'         => 'text',
                'section'      => 'advanced',
                'priority'     => 24,
                'help_text'    => __( 'Text for HTML5 placeholder attribute', 'wpuf-pro' ),
                'dependencies' => array(
                    'multiple' => ''
                )
            ),

            array(
                'name'         => 'default',
                'title'        => __( 'Default value', 'wpuf-pro' ),
                'type'         => 'text',
                'section'      => 'advanced',
                'priority'     => 25,
                'help_text'    => __( 'The default value this field will have', 'wpuf-pro' ),
                'dependencies' => array(
                    'multiple' => ''
                )
            ),

            array(
                'name'         => 'size',
                'title'        => __( 'Size', 'wpuf-pro' ),
                'type'         => 'text',
                'section'      => 'advanced',
                'priority'     => 26,
                'help_text'    => __( 'Size of this input field', 'wpuf-pro' )
            ),
        );

        return array_merge( $default_options, $settings );
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'input_type'        => 'repeat',
            'columns'  => array( __( 'Column 1', 'wpuf-pro' ) ),
            'is_meta'           => 'yes',
            'width'             => 'large',
            'size'              => '40',
            'id'                => 0,
            'is_new'            => true,
            'show_in_post'      => 'yes',
            'hide_field_label'  => 'no',
        );

        return array_merge( $defaults, $props );
    }

    /**
     * Prepare entry
     *
     * @param $field
     *
     * @return mixed
     */
    public function prepare_entry( $field ) {
        $entry_value = '';

        // if it is a multi column repeat field
        if ( isset( $field['multiple'] ) && $field['multiple'] == 'true' ) {

            // if there's any items in the array, process it
            if ( $_POST[$field['name']] ) {
                $ref_arr = array();
                $cols    = count( $field['columns'] );
                $first   = array_shift( array_values( $_POST[$field['name']] ) ); //first element
                $rows    = count( $first );

                // loop through columns
                for ($i = 0; $i < $rows; $i++) {

                    // loop through the rows and store in a temp array
                    $temp = array();
                    for ($j = 0; $j < $cols; $j++) {
                        $temp[] = $_POST[$field['name']][$j][$i];
                    }

                    // store all fields in a row with WP_User_Frontend::$field_separator separated
                    $ref_arr[] = implode( WP_User_Frontend::$field_separator, $temp );
                }

                // now, if we found anything in $ref_arr, store to $multi_repeated
                if ( $ref_arr ) {
                    $multi_repeated[$field['name']] = array_slice( $ref_arr, 0, $rows );
                }

                $entry_value = $multi_repeated[$field['name']];
            }
        } else {
            $entry_value = implode( WP_User_Frontend::$field_separator, $_POST[$field['name']] );
        }

        return sanitize_text_field( trim( $entry_value ) );
    }
}
