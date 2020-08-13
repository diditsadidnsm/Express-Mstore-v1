<?php

/**
 * Coupon class
 *
 * @package WPUF
 */
class WPUF_Coupons {

    private static $_instance;

    function __construct() {

        add_action( 'wp_ajax_coupon_apply', array( $this, 'apply_coupon' ) );
        add_action( 'wp_ajax_nopriv_coupon_apply', array( $this, 'apply_coupon' ) );

        add_action( 'wp_ajax_coupon_cancel', array( $this, 'cancel_coupon' ) );
        add_action( 'wp_ajax_nopriv_coupon_cancel', array( $this, 'cancel_coupon' ) );
    }

    /**
     * Return a singleton instance
     *
     * @return self
     */
    public static function init() {

        if ( !self::$_instance ) {
            self::$_instance = new WPUF_Coupons();
        }

        return self::$_instance;
    }

    /**
     * Cancel a coupon via Ajax
     *
     * It cancels the coupon and returns again the pack details to
     * render on the page.
     *
     * @return array
     */
    function cancel_coupon() {

        check_ajax_referer( 'wpuf_nonce' );

        $pack         = WPUF_Subscription::init()->get_subscription( $_POST['pack_id'] );
        $details_meta = WPUF_Subscription::init()->get_details_meta_value();

        $send_data = array(
            'append_data' => $this->after_apply_coupon( $pack ),
        );

        wp_send_json_success( $send_data );
    }

    /**
     * Populate all coupon meta
     *
     * @param  int $post_id
     * @return array
     */
    function get_coupon_meta( $post_id ) {
        $coupon = array( );

        $coupon['code']        = get_post_meta( $post_id, '_code', true );
        $coupon['package']     = get_post_meta( $post_id, '_package', true );
        $coupon['start_date']  = get_post_meta( $post_id, '_start_date', true );
        $coupon['end_date']    = get_post_meta( $post_id, '_end_date', true );
        $coupon['type']        = get_post_meta( $post_id, '_type', true );
        $coupon['amount']      = get_post_meta( $post_id, '_amount', true );
        $coupon['usage_limit'] = get_post_meta( $post_id, '_usage_limit', true );
        $coupon['access']      = get_post_meta( $post_id, '_access', true );

        return apply_filters( 'wpuf_get_coupon_meta', $coupon, $post_id );
    }

    /**
     * Apply a coupon
     *
     * @return json
     */
    function apply_coupon() {

        check_ajax_referer( 'wpuf_nonce' );

        $pack   = WPUF_Subscription::init()->get_subscription( $_POST['pack_id'] );
        $coupon = get_page_by_title( $_POST['coupon'], 'OBJECT', 'wpuf_coupon' );

        if ( !$coupon ) {
            wp_send_json_error( array( 'message' => __( 'Sorry invalid coupon code!', 'wpuf-pro' ) ) );
        }

        $details_meta  = WPUF_Subscription::init()->get_details_meta_value();
        $coupon_amount = $this->coupon_validation( $pack->meta_value['billing_amount'], $coupon->ID, $_POST['pack_id'] );

        $coupon_usage = get_post_meta( $coupon->ID, '_coupon_used', true );
        $usage_limit  = get_post_meta( $coupon->ID, '_usage_limit', true );

        if ( !empty( $usage_limit) && ((int) $coupon_usage >=  (int) $usage_limit )) {
            wp_send_json_error( array( 'message' => __( 'Sorry, coupon code usage limit is reached!', 'wpuf-pro' ) ) );
        }

        if ( is_wp_error( $coupon_amount ) ) {
            wp_send_json_error( array( 'message' => $coupon_amount->get_error_message() ) );
        }

        $pack->meta_value['billing_amount'] = $coupon_amount;

        $send_data = array(
            'pack_id'     => $pack->ID,
            'coupon_id'   => $coupon->ID,
            'amount'      => apply_filters( 'wpuf_payment_amount', $pack->meta_value['billing_amount'] ),
            'append_data' => $this->after_apply_coupon( $pack ),
            'message'     => __( 'Coupon has been successfully applied.', 'wpuf-pro' )
        );

        wp_send_json_success( $send_data );
    }

    function after_apply_coupon( $pack ) {
        ob_start();
        $pack_cost      = $pack->meta_value['billing_amount'];
        $billing_amount = apply_filters( 'wpuf_payment_amount', $pack->meta_value['billing_amount'] );
        $tax_rate       = wpuf_current_tax_rate() . '%';
        ?>
        <div id="wpuf_type" style="display: none"><?php echo 'pack'; ?></div>
        <div id="wpuf_id" style="display: none"><?php echo $pack->ID; ?></div>
        <div><?php _e( 'Selected Pack ', 'wpuf-pro' ); ?>: <strong><?php echo $pack->post_title; ?></strong></div>
        <div><?php _e( 'Pack Price ', 'wpuf-pro' ); ?>: <strong><span id="wpuf_pay_page_cost"><?php echo wpuf_format_price( $pack_cost ); ?></strong></span></div>
        <?php if ( wpuf_tax_enabled() ) : ?>
        <div><?php _e( 'Tax', 'wpuf-pro' ); ?>: <strong><span id="wpuf_pay_page_tax"><?php echo $tax_rate; ?></strong></span></div>
        <?php endif; ?>

        <div><?php _e( 'Total', 'wpuf-pro' ); ?>: <strong><span id="wpuf_pay_page_total"><?php echo wpuf_format_price( $billing_amount ); ?></strong></span></div>

        <?php
        return ob_get_clean();
    }

    /**
     * Validate a coupon code
     *
     * @param  null|float $billing_amount
     * @param  int $coupon_id
     * @param  int $pack_id
     * @return WP_Error|float
     */
    function coupon_validation( $billing_amount = null, $coupon_id, $pack_id ) {

        $coupon_meta   = $this->get_coupon_meta( $coupon_id );
        $coupon_amount = wpuf_format_price( $coupon_meta['amount'], false );

        if ( empty( $coupon_amount ) ) {
            return wpuf_format_price( $billing_amount, false );
        }

        $coupon_usage = get_post_meta( $coupon_id, '_coupon_used', true );
        $start_date   = !empty( $coupon_meta['start_date'] ) ? strtotime( date( 'Y-m-d', strtotime( $coupon_meta['start_date'] ))) : '';
        $end_date     = !empty( $coupon_meta['end_date'] ) ? strtotime( date( 'Y-m-d', strtotime( $coupon_meta['end_date'] ) ) ) : '';
        $today        = time();
        $pack         = WPUF_Subscription::init()->get_subscription( $pack_id );

        $current_use_email = is_user_logged_in() ? wp_get_current_user()->user_email : '';

        if ( !in_array( 'all', $coupon_meta['package'] ) && !in_array( $pack_id, $coupon_meta['package'] ) ) {
            return new WP_Error( 'message', __( 'Coupon is not availiable for this package!', 'wpuf-pro' ) );
        }

        if ( !empty( $coupon_meta['usage_limit'] ) && $coupon_meta['usage_limit'] < $coupon_usage ) {
            return new WP_Error( 'message', __( 'Coupon usage limit exceeded!', 'wpuf-pro' ) );
        }

        if ( !empty( $start_date ) ) {
            if ( $start_date > $today ) {
                return new WP_Error( 'message', __( 'Sorry, this coupon is not start!', 'wpuf-pro' ) );
            }
        }

        if ( !empty( $end_date ) ) {

            if ( $end_date < $today ) {
                return new WP_Error( 'message', __( 'Sorry, this coupon has been expired!', 'wpuf-pro' ) );
            }
        }

        if ( count( $coupon_meta['access'] ) && !in_array( $current_use_email, $coupon_meta['access'] ) ) {
            return new WP_Error( 'message', __( 'You are not allowed to use this coupon!', 'wpuf-pro' ) );
        }

        if ( $coupon_meta['type'] == 'amount' ) {
            $billing_amount = $pack->meta_value['billing_amount'] - $coupon_amount;
        } else if ( $coupon_meta['type'] == 'percent' ) {
            if ( 0 > $coupon_amount ) {
                $coupon_amount = -1 * $coupon_amount;
            }
            $billing_amount = $pack->meta_value['billing_amount'] - ( $pack->meta_value['billing_amount'] * $coupon_amount ) / 100;
        }

        if ( $billing_amount > 0 ) {
            return wpuf_format_price( $billing_amount, false );
        }

        return 0;
    }

    /**
     * Get discount amount from a coupon
     *
     * @param  float $billing_amount
     * @param  int $coupon_id
     * @param  int $pack_id
     * @return float
     */
    function discount( $billing_amount, $coupon_id, $pack_id ) {
        $amount = $this->coupon_validation( $billing_amount, $coupon_id, $pack_id );

        if ( is_wp_error( $amount ) ) {
            return $amount->get_error_message();
        }

        return wpuf_format_price( $amount, false );
    }

}