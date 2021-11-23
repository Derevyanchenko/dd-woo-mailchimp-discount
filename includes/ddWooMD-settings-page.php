<?php

if ( ! class_exists( 'ddWooMD_Settings_Page' ) ) {

    class ddWooMD_Settings_Page
    {

        public function __construct()
        {
            add_action( 'acf/init', [$this, 'create_settings_page'] );
            add_action( 'acf/init', [$this, 'create_settings_fields'] );
            add_action( 'acf/init', [$this, 'create_dinamic_selects'] );
        }

        /**
         * Create settings page 
         */
        public function create_settings_page()
        {
            if( function_exists('acf_add_options_page') ) {
        
                acf_add_options_page(array(
                    'page_title' 	=> 'ddWooMD Settings',
                    'menu_title'	=> 'ddWooMD Settings',
                    'menu_slug' 	=> 'ddwoomd-settings',
                    'capability'	=> 'edit_posts',
                    'redirect'		=> false
                ));
               
            }
        }

        /**
         * Create settings Fields
         */
        public function create_settings_fields() 
        {
            if( function_exists('acf_add_local_field_group') ):

                acf_add_local_field_group(array(
                    'key' => 'group_6199641e954c9',
                    'title' => 'ddWooMD settings page',
                    'fields' => array(
                        array(
                            'key' => 'field_61996446618f7',
                            'label' => 'General Configuration',
                            'name' => '',
                            'type' => 'tab',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'placement' => 'top',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_61998599c7149',
                            'label' => 'Your shortcode to insert contact form',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '[ddwoomd_form]',
                            'new_lines' => '',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_6199646f618f8',
                            'label' => 'MailChimp API Key',
                            'name' => 'mailchimp_api_key',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6199647c618f9',
                            'label' => 'Select MailChimp List / Audienece Name',
                            'name' => 'audienece_name',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'c6f33b8965' => 'danil',
                            ),
                            'default_value' => false,
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        array(
                            'key' => 'field_619964d9618fa',
                            'label' => 'Coupon Settings',
                            'name' => '',
                            'type' => 'tab',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'placement' => 'top',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_619964e3618fb',
                            'label' => 'Discount type',
                            'name' => 'discount_type',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'percent' => 'Процент скидки',
                                'fixed_cart' => 'Фиксированная скидка на сумму корзины',
                                'fixed_product' => 'Фиксированная скидка на товар',
                            ),
                            'default_value' => 'percent',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        array(
                            'key' => 'field_61996506618fc',
                            'label' => 'Coupon Amount',
                            'name' => 'coupon_amount',
                            'type' => 'number',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 3,
                            'placeholder' => 3,
                            'prepend' => '',
                            'append' => '',
                            'min' => '',
                            'max' => '',
                            'step' => '',
                        ),
                        array(
                            'key' => 'field_61996520618fd',
                            'label' => 'Email Settings',
                            'name' => '',
                            'type' => 'tab',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'placement' => 'top',
                            'endpoint' => 0,
                        ),
                        array(
                            'key' => 'field_619966d2ece10',
                            'label' => 'Success send text',
                            'name' => 'success_send_text',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'The user added successfully to the MailChimp.',
                            'placeholder' => 'The user added successfully to the MailChimp.',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_61996531618fe',
                            'label' => 'Invalid email error text',
                            'name' => 'invalid_email_error_text',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'Something went wrong.',
                            'placeholder' => 'Something went wrong.',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6199655d61901',
                            'label' => 'Email subject',
                            'name' => 'email_subject',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'Your coupon discount code.',
                            'placeholder' => 'Your coupon discount code.',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6199657561902',
                            'label' => 'Email Message',
                            'name' => 'email_message',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'thanks for subscribing! Your coupon discount code:',
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 0,
                            'delay' => 0,
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'options_page',
                                'operator' => '==',
                                'value' => 'ddwoomd-settings',
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
                ));
                
                endif;
        }

        /**
         * Create Custom dinamic select controls
         */
        public function create_dinamic_selects()
        {
            add_filter('acf/load_field/name=discount_type', [$this, 'discount_type_select']);
            add_filter('acf/load_field/name=audienece_name', [$this, 'audienece_name_select']);
        }

        /**
         * creating dinamic values for discount_type select
         */
        function discount_type_select( $field ) {
            $field['choices'] = array();

            $all_discount_types = wc_get_coupon_types();

            if ( ! empty( $all_discount_types ) ) {
                foreach ( $all_discount_types as $type_key => $type_value ) { 

                    $field['choices'][$type_key] = $type_value;

                }
            }

            return $field;
        }

        /**
         * creating dinamic values for audienece_name select
         */
        function audienece_name_select( $field ) {
            $field['choices'] = array();

            $api_key = get_field('mailchimp_api_key', 'option');
            $data_center = substr($api_key,strpos($api_key,'-') + 1);

            $client = new \MailchimpMarketing\ApiClient();
            
            $client->setConfig([
                'apiKey' => $api_key,
                'server' => $data_center
            ]);

            $allLists = $client->lists->getAllLists();
            
            if ( ! (empty( $allLists )) || count($allLists) > 0 ) {
                foreach ( $allLists->lists as $item ) {
                    $field['choices'][$item->id] = $item->name;
                }
            }

            return $field;
        }

    }

    $ddWooMD_Settings_Page = new ddWooMD_Settings_Page();

}