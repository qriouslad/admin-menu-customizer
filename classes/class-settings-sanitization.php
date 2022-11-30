<?php

namespace AMCUST\Classes;

/**
 * Class related to sanitization of settings fields for saving as options
 *
 * @since 1.0.0
 */
class Settings_Sanitization {

	/**
	 * Sanitize options
	 *
	 * @since 1.0.0
	 */
	function sanitize_for_options( $options ) {

		if ( ! isset( $options['custom_menu_order'] ) ) $options['custom_menu_order'] = '';
		// The following fields are added on rendering of custom_menu_order field
		if ( ! isset( $options['custom_menu_titles'] ) ) $options['custom_menu_titles'] = ''; 
		if ( ! isset( $options['custom_menu_hidden'] ) ) $options['custom_menu_hidden'] = '';

		return $options;

	}

}