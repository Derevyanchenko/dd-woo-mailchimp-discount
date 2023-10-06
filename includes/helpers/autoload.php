<?php

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

spl_autoload_register( function ( $class ) {
    $prefix = 'DDWMD\\Inc\\';
    $base_dir = DDWMD_PATH . 'includes/';

    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );

    /**
     * Check if class is an interface or if class name of file contains seperator '_'
     * 
     */
    if ( 
        substr( $relative_class, -9 ) === 'Interface'
        || strpos($relative_class, '_') !== false
    ) {
        $relative_class = str_replace( '_', '-', $relative_class );        
    } 

    /**
     * Check if is Trait and load it
     * 
     */
    if ( strpos($relative_class, 'Traits') !== false ) {
        $trait_arr = explode('\\', $relative_class);
        $trait_dir = $base_dir . strtolower( $trait_arr[0] ) . '/'; 
        $trait_filename = 'trait-' . strtolower( $trait_arr[1] );
        $file = $trait_dir . str_replace( '\\', '/', strtolower( $trait_filename ) ) . '.php';  
    } else {
        /**
         * If it's class
         * 
         */
        $relative_class = 'class-' . strtolower( $relative_class );
        $file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
    }

    if ( file_exists( $file ) ) {
        require_once $file;
    }
});