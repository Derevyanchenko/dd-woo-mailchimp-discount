<?php 
/**
 * Shortcodes class.
 * 
 * @package DDWMD
 */ 

namespace DDWMD\Inc;

use DDWMD\Inc\Traits\Singleton;

if ( ! class_exists( 'Shortcodes' ) ) {
    /**
     * Shortcodes class.
     * 
     */ 
    class Shortcodes
    {
        use Singleton;

        /**
         * Construct method.
         * Contains all hooks for the Shortcodes class. 
         */
        protected function __construct()
        {
            add_action( 'init', array( $this, 'register_shortcode' ) );
        }

        /**
         * Method registers shortcode if it doesn't exist.
         * 
         */
        public function register_shortcode()
        {
            if ( ! shortcode_exists( 'ddwmd_form' ) ) {
                add_shortcode( 'ddwmd_form', array( $this, 'render_form_html' ) );
            }
        }


        /**
         * Method render the html code of the generated newsletter subscription form.
         * 
         */
        public function render_form_html( $atts )
        {
            $default = array(
                'btn_submit_text' => __('Submit', 'DDWMD'),
                'btn_show_submit_icon' => '',
                'class' => '',
                'btn_class' => '',
            );

            $attributes = shortcode_atts( $default, $atts );

            $arrow_icon = '<svg style="color: white; width: 12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.6 278.6l-128 128c-12.51 12.51-32.76 12.49-45.25 0c-12.5-12.5-12.5-32.75 0-45.25L402.8 288H32C14.31 288 0 273.7 0 255.1S14.31 224 32 224h370.8l-73.38-73.38c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l128 128C515.1 245.9 515.1 266.1 502.6 278.6z" fill="white"></path></svg>';

            $btn_submit_text = ! empty( $attributes['btn_show_submit_icon'] ) ? $arrow_icon : $attributes['btn_submit_text'];
            $output = '';

            $output .= '<div class="ddwmd_form_wrapper">';
            $output .= '<form action="#" method="POST" class="ddwmd_form '. $attributes['class'] .'">';

            $output .= '<input type="email" required mame="email" placeholder="'. __('Your email', 'DDWMD') .'" id="ddwmd_email" class="ddwmd_field">';
            $output .= '<button class="ddwmd_submit '. $attributes['btn_class'] .'">' . $btn_submit_text . '</button>';

            $output .= '</form>';
            $output .= '</div>';

            return $output;
        }
    }
}