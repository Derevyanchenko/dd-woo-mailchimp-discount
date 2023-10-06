<?php
/**
 * Mailer class.
 * 
 * @package DDWMD
 */

namespace DDWMD\Inc;

if ( ! class_exists( 'Mailer' ) ) {
    /**
     * Mailer class.
     * 
     */ 
    class Mailer
    {
        /**
         * Send mail with coupon code for the customer.
         *
         * @param int    $subscriber_email_address description.
         * @param object $coupon_code description.
         * 
         * @return string $email_response Success or false.
         */
        public function send_mail( $subscriber_email_address, $coupon_code ) 
        {
            $to = $subscriber_email_address;
            $subject = get_field('email_subject', 'option');  
            $body =  wpautop( stripslashes ( get_field('email_message', 'option') ) ) . '<b>' . $coupon_code . '</b>'; 
            $body = htmlspecialchars_decode($body);
            $mail_headers = "MIME-Version: 1.0" . "\r\n";
            $mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $result = wp_mail($to, $subject, $body, $mail_headers);

            if( $result ) $mail_response = 'success';

            return array(
                'coupon_code' => $coupon_code,
                'email_response' => $mail_response
            );
        }
    }

}
