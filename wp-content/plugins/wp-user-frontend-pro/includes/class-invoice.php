<?php

/**
* Generate Invoice on payments
*
* @since 2.5.6
*/
class WPUF_Pro_Invoice {

    private static $_instance;

    function __construct() {
        require_once WPUF_PRO_INCLUDES . '/libs/invoicr/invoicr.php';

        add_action( 'wpuf_payment_received', array( $this, 'generate_invoice' ), 100, 2 );
        add_action( 'wpuf_admin_subscription_content', array( $this, 'enable_sub_noti_email' ), 10, 1 );
        add_filter( 'wpuf_settings_sections', array( $this, 'wpuf_pro_settings_tab' ) );
        add_filter( 'wpuf_settings_fields', array( $this, 'wpuf_pro_settings_content' ) );
        // date_default_timezone_set(get_option('timezone_string'));
    }

    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function wpuf_pro_settings_tab( $settings ) {

        $settings2 = array(
            array(
                'id'    => 'wpuf_payment_invoices',
                'title' => __( 'Invoices', 'wpuf' ),
                'icon' => 'dashicons-media-spreadsheet'
            )
        );

        return array_merge( $settings, $settings2);
    }

    public function wpuf_pro_settings_content( $settings_fields ) {

        $settings_fields2 = array(
            'wpuf_payment_invoices' => array(
                array(
                    'name'    => 'enable_invoices',
                    'label'   => __( 'Enable Invoices', 'wpuf-pro' ),
                    'desc'    => __( 'Enable sending invoices for completed payments', 'wpuf-pro' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name'    => 'show_invoices',
                    'label'   => __( 'Show Invoices', 'wpuf-pro' ),
                    'desc'    => __( 'Show Invoices option where <code>[wpuf_account]</code> is located', 'wpuf-pro' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name'     => 'set_logo',
                    'label'    => __( 'Set Invoice Logo', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the company Logo to be used in Invoice', 'wpuf-pro' ),
                    'type'     => 'file',
                    'default'  => false,
                ),
                array(
                    'name'     => 'set_color',
                    'label'    => __( 'Set Invoice Color', 'wpuf-pro' ),
                    'desc'     => __( 'Set color code to be used in invoice', 'wpuf-pro' ),
                    'type'     => 'text',
                    'default'  => '#e435226',
                ),
                array(
                    'name'       => 'set_from_address',
                    'label'    => __( 'From Address', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the provider information of the Invoice. Note: use the <xmp class="wpuf-xmp-tag"><br></xmp> tag to enter line breaks.', 'wpuf-pro' ),
                    'type'     => 'textarea',
                ),
                array(
                    'name'       => 'set_title',
                    'label'    => __( 'Invoice Title', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the payment information title of the Invoice', 'wpuf-pro' ),
                    'type'     => 'text',
                ),
                array(
                    'name'       => 'set_paragraph',
                    'label'    => __( 'Invoice Paragraph', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the payment information paragraph of the Invoice', 'wpuf-pro' ),
                    'type'     => 'textarea',
                ),
                array(
                    'name'       => 'set_footernote',
                    'label'    => __( 'Invoice Footer', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the fotter of the Invoice', 'wpuf-pro' ),
                    'type'     => 'text',
                ),
                array(
                    'name'       => 'set_filename',
                    'label'    => __( 'Invoice Filename Prefix', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the filename prefix of the Invoice', 'wpuf-pro' ),
                    'type'     => 'text',
                ),
                array(
                    'name'       => 'set_mail_sub',
                    'label'    => __( 'Set Invoice Mail Subject', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the mail subject of the Invoice', 'wpuf-pro' ),
                    'type'     => 'text',
                ),
                array(
                    'name'       => 'set_mail_body',
                    'label'    => __( 'Set Invoice Mail Body', 'wpuf-pro' ),
                    'desc'     => __( 'This sets the mail body of the Invoice', 'wpuf-pro' ),
                    'type'     => 'textarea',
                )
            )
        );

        return array_merge( $settings_fields, $settings_fields2 );
    }

    public function generate_invoice( $data, $recurring ) {
        global $wpdb;

        $enable_invoices  = wpuf_get_option( 'enable_invoices', 'wpuf_payment_invoices', 'on' );
        $show_invoice     = wpuf_get_option( 'show_invoices', 'wpuf_payment_invoices', 'on' );
        $inv_logo         = wpuf_get_option( 'set_logo', 'wpuf_payment_invoices' );
        $inv_color        = wpuf_get_option( 'set_color', 'wpuf_payment_invoices','#e435226' );
        $inv_from_addr    = wpuf_get_option( 'set_from_address', 'wpuf_payment_invoices' );
        $inv_from_addr    = explode( '<br>', $inv_from_addr );
        $results          = $wpdb->get_results("SELECT payer_address FROM {$wpdb->prefix}wpuf_transaction WHERE user_id={$data['user_id']} ORDER BY id DESC LIMIT 1");
        $inv_to_addr      = array();
        $addr             = array();

        if ( wpuf_get_option( 'show_address', 'wpuf_address_options', false ) ) {
            foreach ( $results as $result ) {
                $addr = $result->payer_address;
                $addr = unserialize( $addr );
            }
        }

        if ( !empty( $data['payer_first_name'] ) && !empty( $data['payer_last_name'] ) ) {
            $inv_to_addr[] = $data['payer_first_name'] . ' ' . $data['payer_last_name'];
        } else {
            $inv_to_addr[] = $data['payer_email'];
        }

        if ( !empty( $addr ) ) {
            foreach ( $addr as $key => $value ) {
                $inv_to_addr[] = $value;
            }
        }

        $inv_title        = wpuf_get_option( 'set_title', 'wpuf_payment_invoices' );
        $inv_para         = wpuf_get_option( 'set_paragraph', 'wpuf_payment_invoices' );
        $inv_foot         = wpuf_get_option( 'set_footernote', 'wpuf_payment_invoices' );
        $inv_filename     = wpuf_get_option( 'set_filename', 'wpuf_payment_invoices' );
        $inv_u_id         = $data['user_id'];
        $inv_status       = $data['status'];
        $inv_cost         = $data['cost'];
        $inv_pack         = $data['pack_id'];
        $inv_u_fname      = $data['payer_first_name'];
        $inv_u_lname      = $data['payer_last_name'];
        $inv_u_email      = $data['payer_email'];
        $inv_payment_type = $data['payment_type'];
        $inv_id           = isset( $data['transaction_id'] ) ? $data['transaction_id'] : 1;
        $inv_dt           = $data['created'];
        $inv_dt           = new DateTime($inv_dt);
        $inv_date         = $inv_dt->format('Y-m-d');

        $currency         = wpuf_get_option( 'currency', 'wpuf_payment', 'USD' );
        $invoice          = new invoicr("A4",$currency,"en");

        $invoice->setNumberFormat('.',',');

        if ( !$inv_logo ) {
            $inv_logo = WPUF_PRO_INCLUDES . '/libs/invoicr/req/dummy_logo.jpg';
            $invoice->setLogo( $inv_logo, 100, 88 );
        } else {
            $invoice->setLogo( $inv_logo, 100, 88 );
        }

        $tax_amount = wpuf_current_tax_rate();
        $invoice->setColor($inv_color);
        $invoice->setType( __("Invoice", "wpuf-pro") );
        $invoice->setReference($inv_id);
        $invoice->setDate($inv_date);
        $invoice->setFrom( $inv_from_addr );
        $invoice->setTo( $inv_to_addr );
        $invoice->addItem($inv_pack,false,$inv_cost,$tax_amount . '%',$inv_cost,false,$inv_cost);
        $invoice->addTotal( __("Total", "wpuf-pro"), $inv_cost );
        $invoice->addTotal( __("Payment Type", "wpuf-pro"), $inv_payment_type );
        $invoice->addTotal( __("Total due", "wpuf-pro"), $inv_cost, true );
        $invoice->addBadge($inv_status);
        $invoice->addTitle($inv_title);
        $invoice->addParagraph($inv_para);
        $invoice->setFooternote($inv_foot);

        $inv_dir = WP_CONTENT_DIR .'/uploads/wpuf-invoices/';

        if (!file_exists( $inv_dir ) ) {
            mkdir( $inv_dir , 0777, true );
        }

        $pdf_file = $inv_dir . "{$inv_u_id}_{$inv_filename}_{$inv_id}.pdf";

        if ( !$pdf_file ) {
            $pdf_file = $inv_u_id . 'invoice.pdf';
        }

        $dl_link = content_url() . '/uploads/wpuf-invoices/' . "{$inv_u_id}_{$inv_filename}_{$inv_id}.pdf";

        update_user_meta( $inv_u_id, '_invoice_link' . $inv_id, $dl_link );

        global $pagenow;

        if ( 'on' == $enable_invoices ) {
            if ( $pagenow == 'profile.php' || $pagenow == 'user-edit.php' )  {
                $invoice->render( $pdf_file, 'F');
                $assign_noti = (get_user_meta( $inv_u_id, '_pack_assign_notification', true ) == 'true') ? true : false;

                if ( $assign_noti ) {
                    $this->send_invoice( $pdf_file, $inv_u_email );
                }
            } else {
                $invoice->render( $pdf_file, 'F');
                $this->send_invoice( $pdf_file, $inv_u_email );
            }
        }

    }

    public function enable_sub_noti_email( $user_id ) {
        $checked = ( get_user_meta( $user_id, '_pack_assign_notification', true ) == 'true' ) ? 'checked' : '';
        ?>
        <table>
            <tr>
            <td><label style="margin-left: 15px;">Send invoice to mail</label></td>
            <td><input type="hidden" name="wpuf_profile_mail_noti" value="false"></td>
            <td><input type="checkbox" name="wpuf_profile_mail_noti" value="true" <?php echo $checked; ?> style="margin-left: 20px;"></td>
            </tr>
        </table>
    <?php
    }

    public function send_invoice( $pdf_file, $inv_u_email ) {

        if ( !file_exists( $pdf_file ) ) {
            return false;
        }

        $attach     = array();
        $to         = $inv_u_email;
        $subj       = wpuf_get_option( 'set_mail_sub', 'wpuf_payment_invoices' );
        $text_body  = wpuf_get_option( 'set_mail_body', 'wpuf_payment_invoices' );

        if ( $subj == '' ) {
            $subj = 'Invoice for your payment';
        }

        if ( $text_body == '' ) {
            $text_body  = "Dear Subscriber,\r\nPlease, check attachment for the invoice of your transaction.";
        }

        $eol        = '';
        $headers    = "MIME-Version: 1.0". $eol;
        $attach     = $pdf_file;

        $mail_body  = get_formatted_mail_body( $text_body, $subj );

        wp_mail( $to, $subj, $mail_body, $headers, $attach );

        //unlink( $pdf_file );

    }

}
