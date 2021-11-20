<?php

if ( ! class_exists( 'ddWooMD_core' ) ) {

    class ddWooMD_core 
    {

        public function __construct()
        {
			// add_action( 'init', [$this, 'mailchimp_integration'] );
        }

        /**
		 * Genetate random coupon name
		 */
		public function generate_random_coupon_name() 
		{
			$characters = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$char_length = "8";
			$random_string = substr( str_shuffle( $characters ),  0, $char_length );

			return $random_string;
		}

		/**
		 * Check if coupon_exists
		 */
		public function coupon_exists($coupon_code) 
		{
			global $wpdb;

			$sql = $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_type = 'shop_coupon' AND post_name = '%s'", $coupon_code );
			$coupon_codes = $wpdb->get_results($sql);

			if ( count( $coupon_codes ) > 0) {
				return true;
			} else return false;
		}

		/**
		 * Create a coupon programatically
		 */
		public function dd_create_coupon_code_programmatically() 
		{

			$coupon_code = $this->generate_random_coupon_name();

			if ( $this->coupon_exists( $coupon_code ) ) {
				$coupon_code = $this->generate_random_coupon_name();
			} 

			$amount = get_field('coupon_amount', 'option'); 
			$discount_type = get_field('discount_type', 'option'); 

			$coupon = array(
				'post_title' => $coupon_code,
				'post_content' => '',
				'post_status' => 'publish',
				'post_author' => 1,
				'post_type' => 'shop_coupon'
			);
			$new_coupon_id = wp_insert_post( $coupon );

			update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
			update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
			update_post_meta( $new_coupon_id, 'individual_use', 'no' );
			update_post_meta( $new_coupon_id, 'product_ids', '' );
			update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
			update_post_meta( $new_coupon_id, 'usage_limit', '1' );
			update_post_meta( $new_coupon_id, 'expiry_date', '' );
			update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
			update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

			return $coupon_code;
		}

		/**
		 * Send mail with coupon code for the customer 
		 */
		public function send_mail_with_coupon_code($subscriber_email_address, $coupon_code) 
		{
			$to = $subscriber_email_address;
			$subject = get_field('email_subject', 'option');  // 'Your coupon discount code.'
			$body =  wpautop( stripslashes ( get_field('email_message', 'option') ) ) . '<br><h3>' . $coupon_code . '</h3>'; 
			$body = htmlspecialchars_decode($body);
			$mail_headers = "MIME-Version: 1.0" . "\r\n";
			$mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			$mailResult = wp_mail($to, $subject, $body, $mail_headers);
			
			if( $mailResult ) $mail_response = 'success';

			return array(
				'coupon_code' => $coupon_code,
				'email_response' => $mail_response
			);
		}

		/*
		 * Create a coupon programatically after user subscribed in mailchimp
		 */
		public function mailchimp_integration($email) 
		{

			$list_id = get_field('audienece_name', 'option');
			$api_key = get_field('mailchimp_api_key', 'option');

			$data_center = substr($api_key,strpos($api_key,'-') + 1);
 
			$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
			
			$json = json_encode([
				'email_address' => $email,
				'status'        => 'subscribed', 
			]);
			
			try {
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
				curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
				$result = curl_exec($ch);
				$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
			
				if (200 == $status_code) {
					// echo "The user added successfully to the MailChimp.";
					echo  get_field('success_send_text', 'option'); 
					$coupon_code = $this->dd_create_coupon_code_programmatically();
					$mail_result_array = $this->send_mail_with_coupon_code($email, $coupon_code);
					// var_dump($mail_result_array);
				}
			} catch(Exception $e) {
				echo get_field('invalid_email_error_text', 'option');
				echo $e->getMessage();
			}
		}

    }

    $ddWooMD_core = new ddWooMD_core();

}