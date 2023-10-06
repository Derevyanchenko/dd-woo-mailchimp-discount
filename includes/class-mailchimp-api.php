<?php
/**
 * Mailchimp_API class.
 * 
 * @package DDWMD
 */

namespace DDWMD\Inc;

if ( ! class_exists( 'Mailchimp_API' ) ) {
    /**
     * Mailchimp_API class.
     * 
     */ 
    class Mailchimp_API
    {
        /**
         * Instance of the mailchimp API Client library.
         * 
         * @var object
         */
        private $client;

        /**
         * Construct method.
         * 
         * Сontains initialization and basic configuration of 
         * the library for working with the Mailchimp service. 
         */
        public function __construct()
        {
            $this->client = new \MailchimpMarketing\ApiClient();

            $api_key     = get_field( 'mailchimp_api_key', 'option' );
			$data_center = substr( $api_key, strpos( $api_key, '-' ) + 1 );

            $this->client->setConfig([
                'apiKey' => $api_key,
                'server' => $data_center
            ]);
        }

        /**
         * Method returns an array of list of all audiences
         *
         * @return array
         */
        public function get_all_lists() {
            return $this->client->lists->getAllLists();
        }

        /**
         * Method are subscribing customer to audienece and after 
         * sending mail with coupon to customer email address.
         *
         * @param string $email customer email address.
         * 
         * @return string $email_response Success or error.
         */
		public function add_list_member( $email ) 
		{
			$list_id = get_field('audienece_name', 'option');

			$response = $this->client->lists->addListMember( $list_id, [
				"email_address" => $email,
				"status" => "subscribed",
			] );

            return $response;
		}
    }

}
