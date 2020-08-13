<?php
/**
 * Plugin Name: Stripe Payment
 * Description: Stripe payment gateway for WP User Frontend
 * Plugin URI: https://wedevs.com/products/plugins/wp-user-frontend-pro/stripe/
 * Thumbnail Name: wpuf-stripe.png
 * Author: weDevs
 * Author URI: http://wedevs.com/
 * Version: 0.1
 * License: GPL2
 * Text Domain: wpuf-stripe
 * Domain Path: languages
 *
 * Copyright (c) 2017 weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPUF_Stripe {
    /**
     * Class constructor.
     */
    public function __construct() {
        // load the addon
        $this->plugin_init();
    }

    /**
     * Initialize the class.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Init the plugin.
     *
     * @return void
     */
    public function plugin_init() {
        include dirname( __FILE__ ) . '/includes/stripe.php';

        // Define constants
        $this->define_constants();

        // Initialize the action hooks
        $this->init_actions();

        // Initialize the filter hooks
        $this->init_filters();
    }

    /**
     * Define the plugin constants.
     *
     * @return void
     */
    private function define_constants() {
        define( 'WPUF_STRIPE_FILE', __FILE__ );
        define( 'WPUF_STRIPE_PATH', dirname( WPUF_STRIPE_FILE ) );
        define( 'WPUF_STRIPE_INCLUDES', WPUF_STRIPE_PATH . '/includes' );
        define( 'WPUF_STRIPE_URL', plugins_url( '', WPUF_STRIPE_FILE ) );
        define( 'WPUF_STRIPE_ASSETS', WPUF_STRIPE_URL . '/assets' );
    }

    /**
     * Init the plugin actions.
     *
     * @return void
     */
    private function init_actions() {
        add_action( 'wp_footer', array( $this, 'footer_scripts' ) );
    }

    /**
     * Init the plugin filters.
     *
     * @return void
     */
    private function init_filters() {
        add_filter( 'wpuf_payment_gateways', array( $this, 'filter_add_gateways' ) );
    }

    /**
     * Filter the gateways
     *
     * @param  array $gateways
     *
     * @return array
     */
    public function filter_add_gateways( $gateways ) {
        $gateways['stripe'] = array(
            'admin_label'    => __( 'Credit Card', 'wpuf-pro' ),
            'checkout_label' => __( 'Credit Card', 'wpuf-pro' ),
            //'icon'           => apply_filters( 'wpuf_stripe_checkout_icon', WPUF_STRIPE_ASSETS . '/images/cards.png' )
        );

        return $gateways;
    }

    /**
     * Include JavaScript codes into footer
     *
     * @return void
     */
    public function footer_scripts() {
        if ( ! isset( $_GET['action'] ) || $_GET['action'] != 'wpuf_pay' ) {
            return;
        }

        $wpuf_stripe_api_key = wpuf_get_option('stripe_api_key', 'wpuf_payment');
        ?>

        <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
        <script>
           (function($){
                function wpufStripeFormBlock(el){
                    el.css({
                        'background': '#fff url("<?php echo WPUF_STRIPE_ASSETS; ?>/images/ajax-loader.gif") no-repeat center',
                        'opacity': '0.7'
                    });
                }

                function wpufStripeFormUnBlock(el){
                    el.css({
                        'background': 'inherit',
                        'opacity': '1'
                    });
                }

                $(window).on('load', function () {
                    var wpufForm                  = $('#wpuf-payment-gateway');
                    var wpufStripeKey             = '<?php echo $wpuf_stripe_api_key; ?>';
                    var wpufStripe                = Stripe(wpufStripeKey);
                    var wpufStripeElements        = wpufStripe.elements();
                    var wpufStripeCard            = wpufStripeElements.create('card');
                    var displayError              = document.getElementById('wpuf-stripe-card-errors');
                    var displaySuccess            = document.getElementById('wpuf-stripe-card-success');

                    wpufStripeCard.mount('#card-element');

                    wpufStripeCard.addEventListener('change', function(event) {
                      if (event.error) {
                        displayError.textContent = event.error.message;
                      } else {
                        displayError.textContent = '';
                      }
                    });

                    wpufForm.submit(function(e) {
                      if ( wpufForm.find( "input[name='wpuf_payment_method']:checked" ).val() != 'stripe' ) {
                        return true;
                      }

                      e.preventDefault();

                      if ( ! window.wpuf_validate_address(e) ) {
                        return;
                      }

                      wpufStripeFormBlock(wpufForm);
                      wpufProcessStripePayment(e);
                    });

                    function wpufProcessStripePayment(e) {
                        var wpufFormData     = wpufForm.serialize();
                        var wpufPaymentType  = $( 'input[name="type"]' ).val();
                        var recurringPay     = $( 'input[name="recurring_pay"]' ).val();

                        if ( recurringPay === 'yes' ) {
                            wpufStripe.createToken( wpufStripeCard ).then( function( result ) {
                                if ( result.error ) {
                                    wpufStripeFormUnBlock(wpufForm);
                                    displayError.textContent = result.error.message;
                                } else {
                                    wpufSendStripePaymentRequest( result.token, wpufFormData, recurringPay);
                                }
                            });
                        } else {
                            wpufSendStripePaymentRequest( '', wpufFormData, recurringPay);
                        }
                    }

                    function wpufSendStripePaymentRequest(stripeToken, form_data, recurringPay) {
                        var requestData = {
                            action: 'wpuf_create_stripe_payment_intent',
                            form_data: form_data,
                            nonce: wpuf_frontend.nonce
                        }

                        if ( stripeToken !== '' ) {
                            requestData.token = stripeToken.id;
                        }

                        $.ajax( {
                            url: wpuf_frontend.ajaxurl,
                            method: 'POST',
                            data: requestData
                        } )
                        .done( function( response ) {
                            if ( typeof response !== 'undefined'
                                && recurringPay === 'yes'
                                && response.status
                                && 'trialing' === response.status
                               ) {
                                wpufForm.append(jQuery('<input type="hidden" name="trialPayment" />').val( 'yes' ));
                                wpufForm.append(jQuery('<input type="hidden" name="stripeSubscriptionId" />').val( response.id ));

                                return wpufSubmitTheForm( response );
                            }

                            if ( typeof response !== 'undefined'
                                && recurringPay === 'yes'
                                && response.status
                                && 'active' === response.status
                               ) {
                                return wpufSubmitTheForm( response );
                            }

                            if ( typeof response !== 'undefined'
                                && recurringPay === 'yes'
                                && response.latest_invoice
                                && response.latest_invoice.payment_intent
                                && response.latest_invoice.payment_intent.client_secret
                              ) {
                                var paymentIntentSecret = response.latest_invoice.payment_intent.client_secret;

                                wpufForm.append(jQuery('<input type="hidden" name="stripeSubscriptionId" />').val( response.id ));

                                WPUFHandleStripePayment( paymentIntentSecret, wpufStripeCard );
                            }

                            if ( response.client_secret !== 'undefined' && recurringPay !== 'yes' ) {
                                var client_secret = response.client_secret;
                                WPUFHandleStripePayment(client_secret, wpufStripeCard);
                            }
                        } );
                    }

                    function WPUFHandleStripePayment(client_secret, card) {
                        wpufStripe.handleCardPayment(
                            client_secret,
                            card
                        ).then(function(response) {
                            return wpufSubmitTheForm( response );
                        });
                    }

                    function wpufSubmitTheForm( response ) {
                        if ( response.error && response.error.message ) {
                            // Display error.message in your UI.
                            wpufStripeFormUnBlock(wpufForm);

                            displayError.textContent    = response.error.message;
                            displaySuccess.textContent  = '';

                            return;
                        }

                        // The payment has succeeded
                        // Display a success message
                        wpufStripeFormUnBlock(wpufForm);

                        displayError.textContent    = "";
                        displaySuccess.textContent  = "<?php echo __('Payment completed! You will be redirected to success page in 10 seconds.', 'wpuf-pro' ); ?>";

                        if ( typeof response !== 'undefined'
                                && response.paymentIntent
                                && response.paymentIntent.status
                                && 'succeeded' ===  response.paymentIntent.status
                            ) {
                                wpufForm.append(jQuery('<input type="hidden" name="stripePaymentIntentId" />').val( response.paymentIntent.id ));
                        }

                        if ( typeof response !== 'undefined'
                                && response.status
                                && 'active' ===  response.status
                            ) {
                                wpufForm.append(jQuery('<input type="hidden" name="stripePaymentIntentId" />').val( response.latest_invoice.payment_intent.id ));
                        }

                        if ( typeof response !== 'undefined'
                                && response.status
                                && 'trialing' ===  response.status
                            ) {
                                wpufForm.append(jQuery('<input type="hidden" name="stripePaymentIntentId" />').val( response.latest_invoice.id ));
                        }

                        wpufForm.get(0).submit();
                    }
                });
            })(jQuery)
        </script>

        <?php
    }

}

WPUF_Stripe::init();
