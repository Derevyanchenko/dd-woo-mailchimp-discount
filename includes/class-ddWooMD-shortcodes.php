<?php

if ( ! class_exists( 'ddEWooMD_Shortcodes' ) ) {

    class ddEWooMD_Shortcodes
    {

        public function __construct()
        {
            add_action( 'init', [$this, 'register_shortcode'] );
        }

        /**
        * Register shortcode method
        * shortcode example: [ddwoomd_form]
        **/ 
        public function register_shortcode()
        {
            add_shortcode( 'ddwoomd_form', [$this, 'ddwoomd_form_html'] );
        }

        /**
        * Render html ajax form method  
        **/ 
        public function ddwoomd_form_html()
        {
            $btn_submit_text = 'Submit';
            $output = '';

            $output .= '<div class="ddwoomd_form_wrapper">';
            $output .= '<form action="#" method="POST" class="ddwoomd_form">';

            $output .= '<input type="email" required mame="email" id="ddwoomd_email" class="ddwoomd_field">';
            $output .= '<button class="ddwoomd_submit">' . $btn_submit_text . '</button>';

            $output .= '</form>';
            $output .= '</div>';

            return $output;
        }

    }


    $ddEWooMD_Shortcodes = new ddEWooMD_Shortcodes();

}