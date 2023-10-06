<?php
/**
 * Main Plugin class.
 * 
 * @package  DDWMD
 */
namespace DDWMD\Inc;

use DDWMD\Inc\Traits\Singleton;


/**
 * Plugin class.
 * 
 */ 
class Plugin
{

	use Singleton;

	/**
	 * Construct method.
	 * Contains init_services method. 
	 */
    protected function __construct()
    {
        self::init_services();
    }

	/**
	 * Method registers all used classes with their namespaces.
	 */
    private static function get_services()
	{
		return [
			Assets::class,
			Shortcodes::class,
			Settings_Page::class,
			Promocode::class,
			Mailer::class,
			Mailchimp_API::class,
			AJAX::class,
		];
	}

	/**
	 * Method traverses all registered classes and, if the class 
	 * has a 'get_instance' method (Singleton pattern), calls it, 
	 * thereby initializing the object of the current class.
	 */
    private static function init_services()
	{
		foreach (self::get_services() as $class) {
			if ( method_exists( $class, 'get_instance' ) ) {
				$class::get_instance();
			}
		}
	}
}