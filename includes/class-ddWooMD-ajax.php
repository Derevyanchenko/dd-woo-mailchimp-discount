<?php

if ( ! class_exists( 'ddWooMD_ajax' ) ) {

    class ddWooMD_ajax
    {

        public $ddWooMD_core;

        public function __construct()
        {
            $this->ddWooMD_core = new ddWooMD_core;
            add_action( 'wp_ajax_send_data_to_mailchimp', [$this, 'send_data_to_mailchimp'] );
            add_action( 'wp_ajax_nopriv_send_data_to_mailchimp', [$this, 'send_data_to_mailchimp'] );
        }

        /**
         * send data to mailchimp ajax method
         */ 
        public function send_data_to_mailchimp()
        {

            if ( ! empty( $_POST ) ) {
                check_ajax_referer( '_wpnonce', 'nonce' );

                $email = $_POST['email'];
                $this->ddWooMD_core->mailchimp_integration($email);
            }

            wp_die();
        }

    }

    $ddWooMD_ajax = new ddWooMD_ajax();

}