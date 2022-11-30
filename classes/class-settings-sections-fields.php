<?php

namespace AMCUST\Classes;

/**
 * Class for registration of settings fields
 *
 * @since 1.0.0
 */
class Settings_Sections_Fields {

	/**
	 * Register plugin settings and the corresponding fields
	 *
	 * @link https://wpshout.com/making-an-admin-options-page-with-the-wordpress-settings-api/
	 * @link https://rudrastyh.com/wordpress/creating-options-pages.html
	 * @since 1.0.0
	 */
	function register_sections_fields() {

		add_settings_section(
			'main-section', // Section ID
			'', // Section title. Can be blank.
			'', // Callback function to output section intro. Can be blank.
			AMCUST_SLUG // Settings page slug
		);

		// Instantiate object for sanitization of settings fields values
		$sanitization = new Settings_Sanitization;

		// Instantiate object for rendering of settings fields for the admin page
		$render_field = new Settings_Fields_Render;

		register_setting( 
			AMCUST_ID, // Option group or option_page
			AMCUST_SLUG_U, // Option name in wp_options table
			array(
				'type'					=> 'array', // 'string', 'boolean', 'integer', 'number', 'array', or 'object'
				'description'			=> '', // A description of the data attached to this setting.
				'sanitize_callback'		=> [ $sanitization, 'sanitize_for_options' ],
				'show_in_rest'			=> false,
				'default'				=> array(), // When calling get_option()
			)
		);

		$field_id = 'custom_menu_order';
		$field_slug = 'custom-menu-order';

		add_settings_field(
			$field_id, // Field ID
			'', // Field title
			[ $render_field, 'render_sortable_menu' ], // Callback to render field with custom arguments in the array below
			AMCUST_SLUG, // Settings page slug
			'main-section', // Section ID
			array(
				'field_id'				=> $field_id, // Custom argument
				'field_name'			=> AMCUST_SLUG_U . '['. $field_id .']', // Custom argument
				'field_type'			=> 'sortable-menu', // Custom argument
				'field_description'		=> '', // Custom argument
				'class'					=> 'amcust-sortable amcust-hide-th ' . $field_slug, // Custom class for the <tr> element
			)
		);

	}

}