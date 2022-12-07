<?php

// We're using the singleton design pattern
// https://code.tutsplus.com/articles/design-patterns-in-wordpress-the-singleton-pattern--wp-31621
// https://carlalexander.ca/singletons-in-wordpress/
// https://torquemag.io/2016/11/singletons-wordpress-good-evil/

/**
 * Main class of the plugin used to add functionalities
 *
 * @since 1.0.0
 */
class Admin_Menu_Customizer {

	// Refers to a single instance of this class
	private static $instance = null;

	/**
	 * Creates or returns a single instance of this class
	 *
	 * @return Admin_Menu_Customizer a single instance of this class
	 * @since 1.0.0
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

	/**
	 * Initialize plugin functionalities
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

		// Setup admin menu, admin page, settings sections, section fields, admin scripts, plugin action links, etc.
		add_action( 'admin_menu', 'amcust_register_admin_menu' );

		// Register plugin settings

		// Instantiate object for registration of settings section and fields
		$settings = new AMCUST\Classes\Settings_Sections_Fields;
		add_action( 'admin_init', [ $settings, 'register_sections_fields' ] );

		// Enqueue admin scripts and styles only on the plugin's main page
		add_action( 'admin_enqueue_scripts', 'amcust_admin_scripts' );

		// Add action links in plugins page
		add_filter( 'plugin_action_links_' . AMCUST_SLUG . '/' . AMCUST_SLUG . '.php', 'amcust_plugin_action_links' );

		// Update footer text
		add_filter( 'admin_footer_text', 'amcust_footer_text' );

		// Suppress all notices on the plugin's main page. Then add notification for successful settings update.
		add_action( 'admin_notices', 'amcust_suppress_notices', 9 );
		add_action( 'all_admin_notices', 'amcust_suppress_generic_notices', 9 );

		// Instantiate object for customizing the admin menu
		$admin_menu = new AMCUST\Classes\Custom_Admin_Menu;

		// Apply the customizations to admin menu

		$options = get_option( AMCUST_SLUG_U, array() );

		if ( array_key_exists( 'custom_menu_order', $options ) ) {
			add_filter( 'custom_menu_order', '__return_true' );
			add_filter( 'menu_order', [ $admin_menu, 'render_custom_menu_order' ] );
		}
		if ( array_key_exists( 'custom_menu_titles', $options ) ) {
			add_action( 'admin_menu', [ $admin_menu, 'apply_custom_menu_item_titles' ], 1000 );			
		}
		if ( array_key_exists( 'custom_menu_hidden', $options ) ) {
			add_action( 'admin_menu', [ $admin_menu, 'hide_menu_items' ], 1001 );
			add_action( 'admin_menu', [ $admin_menu, 'add_hidden_menu_toggle' ], 1002 );			
		}
	}

}

Admin_Menu_Customizer::get_instance();