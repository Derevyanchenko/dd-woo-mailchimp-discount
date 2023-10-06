<?php
/**
 * Promocode class.
 * 
 * @package DDWMD
 */

namespace DDWMD\Inc;

if ( ! class_exists( 'Promocode' ) ) {
    /**
     * Promocode class.
     * 
     */ 
    class Promocode
    {
        /**
        * Generate random name for coupon code.
        *
        * @return string $name Generated promocode name.
        */
		private function generate_coupon_name()
		{
			return substr( str_shuffle( "ABCDEFGHJKMNPQRSTUVWXYZ23456789" ),  0, 8 );
		}

        /**
        * Method check if coupon code exists in database.
		*
		* @param string $code Coupon code name.
        *
        * @return boolean true if coupon already exists in database and false if not.
        */
		private function is_coupon_exists( $code ) 
		{
			global $wpdb;

			$sql = $wpdb->prepare( 
				"SELECT post_name 
				FROM $wpdb->posts 
				WHERE post_type = 'shop_coupon' 
				AND post_name = '%s'", 
				$code 
			);

			$results = $wpdb->get_results( $sql );

			if ( count( $results ) > 0) return true;

			return false;
		}


		/**
		 * Create a coupon programatically.
		 * 
		 * @return string $coupon_code.
		 */
		public function create_coupon_code() 
		{
			$coupon_code = $this->generate_coupon_name();

			if ( $this->is_coupon_exists( $coupon_code ) ) {
				$coupon_code = $this->generate_coupon_name();
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
    }

}
