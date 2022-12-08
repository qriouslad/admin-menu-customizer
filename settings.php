<?php

/**
 * Register admin menu
 *
 * @since 1.0.0
 */
function amcust_register_admin_menu() {

	add_submenu_page(
		'options-general.php', // Parent page/menu
		'Admin Menu Customizer', // Browser tab/window title
		'Admin Menu', // Sube menu title
		'manage_options', // Minimal user capabililty
		AMCUST_SLUG, // Page slug. Shows up in URL.
		'amcust_add_settings_page'
	);

}

/**
 * Create the settings page of the plugin
 *
 * @since 1.0.0
 */
function amcust_add_settings_page() {
	?>
	<div class="wrap amcust">

		<div id="amcust-header" class="amcust-header">
			<div class="amcust-header-left">
				<h1 class="amcust-heading"><?php echo get_admin_page_title(); ?> <small><?php esc_html_e( 'by', 'admin-menu-customizer' ); ?> <a href="https://bowo.io" target="_blank">bowo.io</a></small></h1>
				<!-- <a href="https://wordpress.org/plugins/admin-menu-customizer/" target="_blank" class="amcust-header-action"><span>&#8505;</span> <?php // esc_html_e( 'Info', 'admin-menu-customizer' ); ?></a> -->
				<a href="https://wordpress.org/plugins/admin-menu-customizer/#reviews" target="_blank" class="amcust-header-action"><span>&starf;</span> <?php esc_html_e( 'Review', 'admin-menu-customizer' ); ?></a>
				<a href="https://wordpress.org/support/plugin/admin-menu-customizer/" target="_blank" class="amcust-header-action">&#10010; <?php esc_html_e( 'Feedback', 'admin-menu-customizer' ); ?></a>
				<a href="https://paypal.me/qriouslad" target="_blank" class="amcust-header-action">&#9829; <?php esc_html_e( 'Donate', 'admin-menu-customizer' ); ?></a>
			</div>
			<div class="amcust-header-right">
				<a class="button button-primary amcust-save-button">Save Changes</a>
				<div class="amcust-changes-saved" style="display:none;">Changes have been saved.</div>
			</div>
		</div>

		<div class="amcust-body">
			<form action="options.php" method="post">
				<div><!-- Hide to prevent flash of fields appearing at the bottom of the page -->
					<?php settings_fields( AMCUST_ID ); ?>
					<?php do_settings_sections( AMCUST_SLUG ); ?>
					<?php submit_button(
						'Save Changes', // Button copy
						'primary', // Type: 'primary', 'small', or 'large'
						'submit', // The 'name' attribute
						true, // Whether to wrap in <p> tag
						array( 'id' => 'amcust-submit' ), // additional attributes
					); ?>
				</div>
			</form>
		</div>

		<div class="amcust-footer">
		</div>

	</div>
	<?php
}

/**
 * Enqueue admin scripts
 *
 * @since 1.0.0
 */
function amcust_admin_scripts( $hook_suffix ) {

	global $wp_version;

	if ( is_amcust() ) {

		wp_enqueue_script( 'amcust-jsticky', AMCUST_URL . 'assets/js/jquery.jsticky.mod.min.js', array( 'jquery' ), AMCUST_VERSION, false );

		// jQuery UI Sortables. In use, e.g. for Admin Interface >> Admin Menu Organizer	
		// Re-register and re-enqueue jQuery UI Core and plugins required for sortable, draggable and droppable when ordering menu items
		wp_deregister_script( 'jquery-ui-core' );
		wp_register_script( 'jquery-ui-core', get_site_url() . '/wp-includes/js/jquery/ui/core.min.js', array( 'jquery' ), AMCUST_VERSION, false );
		wp_enqueue_script( 'jquery-ui-core' );

		if ( version_compare( $wp_version, '5.6.0', '>=' ) ) {

			wp_deregister_script( 'jquery-ui-mouse' );
			wp_register_script( 'jquery-ui-mouse', get_site_url() . '/wp-includes/js/jquery/ui/mouse.min.js', array( 'jquery-ui-core' ), ASENHA_VERSION, false );
			wp_enqueue_script( 'jquery-ui-mouse' );

		} else {

			wp_deregister_script( 'jquery-ui-widget' );
			wp_register_script( 'jquery-ui-widget', get_site_url() . '/wp-includes/js/jquery/ui/widget.min.js', array( 'jquery' ), ASENHA_VERSION, false );
			wp_enqueue_script( 'jquery-ui-widget' );

			wp_deregister_script( 'jquery-ui-mouse' );
			wp_register_script( 'jquery-ui-mouse', get_site_url() . '/wp-includes/js/jquery/ui/mouse.min.js', array( 'jquery-ui-core', 'jquery-ui-widget' ), ASENHA_VERSION, false );
			wp_enqueue_script( 'jquery-ui-mouse' );

		}

		wp_deregister_script( 'jquery-ui-sortable' );
		wp_register_script( 'jquery-ui-sortable', get_site_url() . '/wp-includes/js/jquery/ui/sortable.min.js', array( 'jquery-ui-mouse' ), AMCUST_VERSION, false );
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_deregister_script( 'jquery-ui-draggable' );
		wp_register_script( 'jquery-ui-draggable', get_site_url() . '/wp-includes/js/jquery/ui/draggable.min.js', array( 'jquery-ui-mouse' ), AMCUST_VERSION, false );
		wp_enqueue_script( 'jquery-ui-draggable' );

		wp_deregister_script( 'jquery-ui-droppable' );
		wp_register_script( 'jquery-ui-droppable', get_site_url() . '/wp-includes/js/jquery/ui/droppable.min.js', array( 'jquery-ui-draggable' ), AMCUST_VERSION, false );
		wp_enqueue_script( 'jquery-ui-droppable' );

		// Main style and script for the admin page
		wp_enqueue_style( 'amcust-admin-page', AMCUST_URL . 'assets/css/admin-page.css', array(), AMCUST_VERSION );
		wp_enqueue_script( 'amcust-admin-page', AMCUST_URL . 'assets/js/admin-page.js', array( 'amcust-jsticky' ), AMCUST_VERSION, false );

	}

}

/**
 * Add 'Customize now' plugin action link.
 *
 * @since    1.0.0
 */

function amcust_plugin_action_links( $links ) {

	$settings_link = '<a href="options-general.php?page=' . AMCUST_SLUG . '">Customize now</a>';

	array_unshift($links, $settings_link); 

	return $links; 

}

/**
 * Modify footer text
 *
 * @since 1.0.0
 */
function amcust_footer_text() {

	if ( is_amcust() ) {
		?>
		<a href="https://wordpress.org/plugins/admin-menu-customizer/" target="_blank">Admin Menu Customizer</a> is on <a href="https://github.com/qriouslad/admin-menu-customizer" target="_blank">github</a>.
		<?php
	}

}

/**
 * Check if current screen is this plugin's main page
 *
 * @since 1.0.0
 */
function is_amcust() {

	$request_uri = sanitize_text_field( $_SERVER['REQUEST_URI'] ); // e.g. /wp-admin/index.php?page=page-slug

	if ( strpos( $request_uri, 'page=' . AMCUST_SLUG ) !== false ) {
		return true; // Yes, this is the plugin's main page
	} else {
		return false; // Nope, this is NOT the plugin's page
	}

}

/**
 * Suppress all notices, then add notice for successful settings update
 *
 * @since 1.1.0
 */
function amcust_suppress_notices() {

	global $plugin_page;

	// Suppress all notices

	if ( AMCUST_SLUG === $plugin_page ) {

		remove_all_actions( 'admin_notices' );

	}

	// Add notice for successful settings update

	if (
		isset( $_GET[ 'page' ] ) 
		&& AMCUST_SLUG == $_GET[ 'page' ]
		&& isset( $_GET[ 'settings-updated' ] ) 
		&& true == $_GET[ 'settings-updated' ]
	) {
		?>
			<script>
				jQuery(document).ready( function() {
					jQuery('.amcust-changes-saved').fadeIn(400).delay(2500).fadeOut(400);
				});
			</script>

		<?php
	}
}

/**
 * Suppress all generic notices on the plugin settings page
 *
 * @since 2.7.0
 */
function amcust_suppress_generic_notices() {

	global $plugin_page;

	// Suppress all notices

	if ( AMCUST_SLUG === $plugin_page ) {

		remove_all_actions( 'all_admin_notices' );

	}

}