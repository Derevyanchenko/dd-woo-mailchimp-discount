<?php
/**
 * Assets class.
 * 
 * @package  DDWMD
 */

namespace DDWMD\Inc;

use DDWMD\Inc\Traits\Singleton;


if ( ! class_exists( 'Assets' ) ) {
    /**
     * Assets class.
     * 
     */ 
    class Assets
    {

        use Singleton;

        /**
         * Construct method.
         * Contains all hooks for the Assets class. 
         * 
         */
        protected function __construct()
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        }

        /**
         * Enqueue frontend scripts method.
         * 
         */
        public function enqueue_scripts()
        {
            wp_register_script( 
                'DDWMD_main_script',  
                plugins_url( '/assets/js/main.js', __DIR__ ), 
                array( 'jquery' ), 
                time() 
            );
            
            wp_localize_script(
                'DDWMD_main_script', 
                'DDWMD_ajax', 
                array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( '_wpnonce' ),
                    'title' => esc_html__( 'DDWMD test title', 'DDWMD' ),
                )
            );

            wp_enqueue_script( 'DDWMD_main_script' );
        }

        /**
         * Enqueue frontend styles method.
         * 
         */
        public function enqueue_styles()
        {
            wp_enqueue_style( 
                'DDWMD_main_style', 
                plugins_url( '/assets/css/main.css', __DIR__ ), 
            );
        }
        
    }
}