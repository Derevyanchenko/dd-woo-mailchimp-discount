<?php
/*
Plugin Name: DD WooCommerce Mailchimp Discount
Plugin URI: #
Description:  DD WooCommerce Mailchimp Discount
Version: 1.0
Author: AMW - Art My Web
Author URI: #
Licence: GPLv2 or later
Text Domain: DDWOOMD
Domain Path: /lang
*/ 

if( ! defined('ABSPATH') ) {
    die;
}  
/******************************************
 * WooCommerce discount Mailchimp
 *****************************************/


if ( ! class_exists( 'ddWooMD' ) ) {
	
	class ddWooMD
	{

		public function __construct() 
		{
			// add_action( 'init', [$this, 'mailchimp_integration'] );
		}

		/**
		 * Genetate random coupon name
		 */
		public function generate_random_coupon_name() {
			$characters = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$char_length = "8";
			$random_string = substr( str_shuffle( $characters ),  0, $char_length );

			return $random_string;
		}

		/**
		 * Check if coupon_exists
		 */
		public function coupon_exists($coupon_code) {
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
		public function dd_create_coupon_code_programmatically() {

			$options = get_option('dd_settings_options');
			$coupon_code = $this->generate_random_coupon_name();

			if ( $this->coupon_exists( $coupon_code ) ) {
				$coupon_code = $this->generate_random_coupon_name();
			} 

			$amount = $options['amount_field']; 
			$discount_type = $options['discount_type_field']; 

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
		public function send_mail_with_coupon_code($subscriber_email_address, $coupon_code) {

			$options = get_option('dd_settings_options');
			$to = $subscriber_email_address;
			$subject = $options['mail_title_field'];  // 'Your coupon discount code.'
			$body =  wpautop( stripslashes ( $options['mail_text_field'] ) ) . '<br><h3>' . $coupon_code . '</h3>'; 
			$body = htmlspecialchars_decode($body);
			$mail_headers = "MIME-Version: 1.0" . "\r\n";
			$mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			$mailResult = mail($to, $subject, $body, $mail_headers);
			
			if( $mailResult ) $mail_response = 'success';

			return array(
				'coupon_code' => $coupon_code,
				'email_response' => $mail_response
			);

		}

		/*
		 * Create a coupon programatically after user subscribed in mailchimp
		 */
		public function mailchimp_integration() 
		{
			$email = 'danildere111222444@gmail.com';
			$list_id = 'c6f33b8965';
			$api_key = '33685714655b1bcae57573a0e91660d8-us5';

			$data_center = substr($api_key,strpos($api_key,'-') + 1);
 
			$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
			
			$json = json_encode([
				'email_address' => $email,
				'status'        => 'subscribed', //pass 'subscribed' or 'pending'
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
					echo "The user added successfully to the MailChimp. <br><br>";
					$coupon_code = $this->dd_create_coupon_code_programmatically();
					$mail_result_array = $this->send_mail_with_coupon_code($email, $coupon_code);
					var_dump($mail_result_array);
					wp_die();
				}
			} catch(Exception $e) {
				echo $e->getMessage();
			}
		}

	}

}

$ddWooMD = new ddWooMD();


/**
 * 6. Register  settings page 
 */

add_action('admin_menu', 'dd_add_menu_item' , 9);    
add_action('admin_init', 'dd_settings_init' );

function dd_add_menu_item() {
	add_menu_page(
		'AMW mailchimp settings',
		'AMW mailchimp settings',
		'manage_options',
		'amw_mailchimp_settings',
		'main_admin_page_callback',
		'dashicons-admin-plugins',
		100
	);
}

function dd_settings_init() {
	// $option_group, $option_name
	register_setting('dd_settings', 'dd_settings_options');

	// $id, $title, $callback, $page
	add_settings_section(
		'dd_settings_section', 
		'',
		'dd_settings_section_html',
		'dd_settings'
	);

	// $id, $title, $callback, $page, $section, $args 
	add_settings_field(
		'amount_field', 
		'Amount:',
		'amount_field_html',
		'dd_settings',
		'dd_settings_section'
	);

	// $id, $title, $callback, $page, $section, $args 
	add_settings_field(
		'discount_type_field', 
		'Discount Type:',
		'discount_type_field_html',
		'dd_settings',
		'dd_settings_section'
	);

	// $id, $title, $callback, $page, $section, $args 
	add_settings_field(
		'mail_title_field', 
		'Mail Title (Subject):',
		'mail_title_field_html',
		'dd_settings',
		'dd_settings_section'
	);	

	// $id, $title, $callback, $page, $section, $args 
	add_settings_field(
		'mail_text_field', 
		'Mail Text:',
		'mail_text_field_html',
		'dd_settings',
		'dd_settings_section'
	);
}

function dd_settings_section_html() {
	?>
		<h1>AMW mailchimp Discount - Settings Page</h1>
	<?php  
}


/**
 * Render Html of amount field 
 */

function amount_field_html() {
	$options = get_option('dd_settings_options');
	?>
		<input type="text" name="dd_settings_options[amount_field]" value="<?php echo isset( $options['amount_field'] ) ? $options['amount_field'] : ''; ?>">
	<?php
}

/**
 * Render Html of mail title field 
 */
function discount_type_field_html() {
	$options = get_option('dd_settings_options');
	$all_discount_types = wc_get_coupon_types();

	echo sprintf(
		'<select id="%s" class="post_form" name="%s">',
		'dd_settings_options[discount_type_field]',
		'dd_settings_options[discount_type_field]'
	);
		if ( ! empty( $all_discount_types ) ) : 
			foreach ( $all_discount_types as $type_key => $type_value ): 

				echo sprintf(
					'<option value="%s" %s>%s</option>',
					$type_key,
					$options['discount_type_field'] == $type_key ? "selected" : '',
					$type_value
				);

			endforeach;
		endif;
	echo "</select>";
}

/**
 * Render Html of mail title field 
 */
function mail_title_field_html() {
	$options = get_option('dd_settings_options');
	?>
		<input type="text" class="dd-input-full-width" name="dd_settings_options[mail_title_field]" value="<?php echo isset( $options['mail_title_field'] ) ? $options['mail_title_field'] : ''; ?>">
	<?php
}

/**
 * Render Html of mail text field 
 */
function mail_text_field_html() {
	$options = get_option('dd_settings_options');

	wp_editor( $options['mail_text_field'], 'text', array(
		'textarea_name' => 'dd_settings_options[mail_text_field]',
		'media_buttons' => false,
		'textarea_rows' => 10,
		'placeholder' => 'Test placeholder',
	));

}

function main_admin_page_callback() {
	?>
	<div class="content dd_settings_page_content">
		<?php settings_errors(); ?>
		<form method="POST" action="options.php">
			<?php
				settings_fields('dd_settings');
				do_settings_sections('dd_settings');
				submit_button();
			?>
		</form>
	</div>
	<style>
		.dd_settings_page_content .wp-editor-wrap,
		.dd-input-full-width {
			max-width: 850px;
			width: 100%;
		}
		
	</style>
	<?php
}