<?php
/**
 * AJAX class.
 * 
 * @package DDWMD
 */

namespace DDWMD\Inc;

use DDWMD\Inc\Traits\Singleton;

if ( ! class_exists( 'AJAX' ) ) {
    /**
     * AJAX class.
     * 
     */ 
    class AJAX
    {
        use Singleton;

        /**
         * Instance of the Promocode class.
         *
         * @var object
         */
        private $promocode_manager;

        /**
         * Instance of the Mailer class.
         *
         * @var object
         */
        private $mailer;

        /**
         * Instance of the Mailchimp API class.
         *
         * @var object
         */
        private $mailchimp_api;

        /**
         * Construct method.
         * Contains instance of needed packages and register ajax callback.
         * 
         */ 
		protected function __construct() {
            $this->promocode_manager = new Promocode();
            $this->mailer            = new Mailer();
            $this->mailchimp_api     = new Mailchimp_API();

            add_action( 'wp_ajax_ddwmd_integrate', array( $this, 'ddwmd_integrate' ) );
            add_action( 'wp_ajax_nopriv_ddwmd_integrate', array( $this, 'ddwmd_integrate' ) );
        }

        /**
         * Method are subscribing customer to audienece and after 
         * sending mail with coupon to customer email address.
         *
         * @param string $email customer email address.
         * 
         * @return string $response Success or error with status and message.
         */
		public function ddwmd_integrate( $email ) 
		{
            if ( ! empty( $_POST ) ) {

                check_ajax_referer( '_wpnonce', 'nonce' );

                $email = sanitize_email( $_POST['email'] );

                $subscription = $this->mailchimp_api->add_list_member( $email );

                if ( $subscription->status == "subscribed" ) {

                    $success_message = get_field('success_send_text', 'option'); 
                    $coupon_code     = $this->promocode_manager->create_coupon_code();
                    $mail_result     = $this->mailer->send_mail( $email, $coupon_code );

                    return wp_send_json_success( array( 
                        'message' => $success_message 
                    ) );
                }
            }

            return wp_send_json_error( array(
                'message' => __( 'Something went wrong.' ),
            ) );
		}
    }

}
