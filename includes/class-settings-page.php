<?php
/**
 * Settings page class.
 * 
 * @package  DDWMD
 */

namespace DDWMD\Inc;

use DDWMD\Inc\Traits\Singleton;


if ( ! class_exists( 'Settings_Page' ) ) {
    /**
     * Settings page class.
     * 
     */ 
    class Settings_Page
    {
        use Singleton;

        /**
         * Construct method.
         * Contains all hooks for the Settings Page. 
         */
        protected function __construct()
        {
            add_action( 'acf/init', array( $this, 'create_settings_page') );
            add_action( 'acf/init', array( $this, 'create_settings_fields') );
            add_action( 'acf/init', array( $this, 'create_dinamic_selects') );
            add_filter('acf/settings/show_admin', array( $this, 'hide_acf_admin_menu' ) );
        }

        /**
         * Hide the ACF admin menu item. 
         * 
         * @param bool $show admin.
         * 
         * @return bool
         */
        public function hide_acf_admin_menu( $show_admin ) {
            return false;
        }

        /**
         * Create settings page.
         * 
         */
        public function create_settings_page()
        {
            if ( function_exists( 'acf_add_options_page' ) ) {
                acf_add_options_page( array(
                    'page_title' 	=> 'DDWMD Settings',
                    'menu_title'	=> 'DDWMD Settings',
                    'menu_slug' 	=> 'DDWMD-settings',
                    'capability'	=> 'edit_posts',
                    'redirect'		=> false
                ) );
            }
        }

        /**
         * Create settings Fields.
         * 
         */
        public function create_settings_fields() 
        {
            if ( function_exists( 'acf_add_local_field_group' ) ) {
                acf_add_local_field_group( array(
                    'key' => 'group_6199641e954c9',
                    'title' => 'DDWMD settings page',
                    'fields' => ddwmd_return_acf_fields() ,
                    'location' => array(
                        array(
                            array(
                                'param' => 'options_page',
                                'operator' => '==',
                                'value' => 'DDWMD-settings',
                            ),
                        ),
                    ),
                    'menu_order' => 0,
                    'position' => 'acf_after_title',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => true,
                    'description' => '',
                ) );
            }
        }

        /**
         * Create Custom dinamic select controls.
         * 
         */
        public function create_dinamic_selects()
        {
            add_filter( 'acf/load_field/name=discount_type', array( $this, 'discount_type_select' ) );
            add_filter( 'acf/load_field/name=audienece_name', array( $this, 'audienece_name_select' ) );
        }

        /**
         * Creating dinamic values for discount_type select.
         * 
         * @return array Fields array. 
         */
        public function discount_type_select( $field ) {
            $field[ 'choices' ] = array();
            $all_discount_types = wc_get_coupon_types();

            if ( ! empty( $all_discount_types ) ) {
                foreach ( $all_discount_types as $type_key => $type_value ) { 
                    $field[ 'choices' ][ $type_key ] = $type_value;
                }
            }

            return $field;
        }

        /**
         * Creating dinamic values for audienece_name select.
         * 
         * @return array Fields array.
         */
        public function audienece_name_select( $field ) {
            $field['choices'] = array();

            $mailchimp_api = new Mailchimp_API();
            $all_lists     = $mailchimp_api->get_all_lists();
                
            if ( ! ( empty( $all_lists ) ) || count( $all_lists ) > 0 ) {
                foreach ( $all_lists->lists as $list ) {
                    $field[ 'choices' ][ $list->id ] = $list->name;
                }
            }

            return $field;
        }

    }

}