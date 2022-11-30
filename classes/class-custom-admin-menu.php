<?php

namespace AMCUST\Classes;

/**
 * Class for applying customization to the admin menu
 *
 * @since 1.0.0
 */
class Custom_Admin_Menu {

	/**
	 * Render custom menu order
	 *
	 * @param $menu_order array an ordered array of menu items
	 * @link https://developer.wordpress.org/reference/hooks/menu_order/
	 * @since 1.0.0
	 */
	public function render_custom_menu_order( $menu_order ) {

		global $menu;

		$options = get_option( AMCUST_SLUG_U );

		// Get current menu order. We're not using the default $menu_order which uses index.php, edit.php as array values.

		$current_menu_order = array();

		foreach ( $menu as $menu_key => $menu_info ) {

			if ( false !== strpos( $menu_info[4], 'wp-menu-separator' ) ) {
				$menu_item_id = $menu_info[2];
			} else {
				$menu_item_id = $menu_info[5];
			}

			$current_menu_order[] = array( $menu_item_id, $menu_info[2] );

		}

		// Get custom menu order

		if ( array_key_exists( 'custom_menu_order', $options ) ) {
			$custom_menu_order = $options['custom_menu_order']; // comma separated
		} else {
			$custom_menu_order = '';
		}

		$custom_menu_order = explode( ",", $custom_menu_order ); // array of menu ID, e.g. menu-dashboard

		// Return menu order for rendering

		$rendered_menu_order = array();

		foreach ( $custom_menu_order as $custom_menu_item_id ) {

			foreach ( $current_menu_order as $current_menu_item_id => $current_menu_item ) {

				if ( $custom_menu_item_id == $current_menu_item[0] ) {

					$rendered_menu_order[] = $current_menu_item[1];

				}

			}

		}

		return $rendered_menu_order;

	}

	/**
	 * Apply custom menu item titles
	 *
	 * @since 1.0.0
	 */
	public function apply_custom_menu_item_titles() {

		global $menu;

		$options = get_option( AMCUST_SLUG_U );

		// Get custom menu item titles
		if ( array_key_exists( 'custom_menu_titles', $options ) ) {
			$custom_menu_titles = $options['custom_menu_titles'];
			$custom_menu_titles = explode( ',', $custom_menu_titles );
		} else {
			$custom_menu_titles = array();
		}	

		$i = 1;

		foreach ( $menu as $menu_key => $menu_info ) {

			do_action( 'inspect', [ 'menu_key_' . $i, $menu_key ] );
			do_action( 'inspect', [ 'menu_info_' . $i, $menu_info ] );

			if ( false !== strpos( $menu_info[4], 'wp-menu-separator' ) ) {
				$menu_item_id = $menu_info[2];
			} else {
				$menu_item_id = $menu_info[5];
			}

			// Get defaul/custom menu item title
			foreach ( $custom_menu_titles as $custom_menu_title ) {

				// At this point, $custom_menu_title value looks like toplevel_page_snippets__Code Snippets

				$custom_menu_title = explode( '__', $custom_menu_title );

				if ( $custom_menu_title[0] == $menu_item_id ) {
					$menu_item_title = $custom_menu_title[1]; // e.g. Code Snippets
					break; // stop foreach loop so $menu_item_title is not overwritten in the next iteration
				} else {
					$menu_item_title = $menu_info[0];
				}

			}

			$menu[$menu_key][0] = $menu_item_title;

			$i++;

		}
	}

	/**
	 * Hide menu items by adding 'hidden' class (part of WP Core's common.css)
	 *
	 * @since 1.0.0
	 */
	public function hide_menu_items() {

		global $menu;

		$options = get_option( AMCUST_SLUG_U );

		// Get hidden menu items

		if ( array_key_exists( 'custom_menu_hidden', $options ) ) {
			$hidden_menu = $options['custom_menu_hidden'];
			$hidden_menu = explode( ',', $hidden_menu );
		} else {
			$hidden_menu = array();
		}

		foreach ( $menu as $menu_key => $menu_info ) {

			if ( false !== strpos( $menu_info[4], 'wp-menu-separator' ) ) {
				$menu_item_id = $menu_info[2];
			} else {
				$menu_item_id = $menu_info[5];
			}

			if ( in_array( $menu_item_id, $hidden_menu ) ) {

				$menu[$menu_key][4] = $menu_info[4] . ' hidden amcust_hidden_menu';

			}

		}

	}

	/**
	 * Add toggle to show hidden menu items
	 *
	 * @since 1.0.0
	 */
	public function add_hidden_menu_toggle() {

		$options = get_option( AMCUST_SLUG_U );

		// Get hidden menu items

		if ( array_key_exists( 'custom_menu_hidden', $options ) ) {
			$hidden_menu = $options['custom_menu_hidden'];
		} else {
			$hidden_menu = '';
		}

		if ( ! empty( $hidden_menu ) ) {

			add_menu_page(
				'Show All',
				'Show All',
				'manage_options',
				'amcust_show_hidden_menu',
				function () {  return false;  },
				"dashicons-arrow-down-alt2",
				300 // position
			);

			add_menu_page(
				'Show Less',
				'Show Less',
				'manage_options',
				'amcust_hide_hidden_menu',
				function () {  return false;  },
				"dashicons-arrow-up-alt2",
				301 // position
			);
		}

	}

}