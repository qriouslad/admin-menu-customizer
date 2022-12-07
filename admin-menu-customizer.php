<?php

/**
 * Plugin Name:       Admin Menu Customizer
 * Plugin URI:        https://wordpress.org/plugins/admin-menu-customizer/
 * Description:       Customize admin menu by reordering items, changing item titles or hiding some items
 * Version:           1.1.1
 * Author:            Bowo
 * Author URI:        https://bowo.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       admin-menu-customizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AMCUST_VERSION', '1.1.1' );
define( 'AMCUST_ID', 'amcust' );
define( 'AMCUST_SLUG', 'admin-menu-customizer' );
define( 'AMCUST_SLUG_U', 'admin_menu_customizer' );
define( 'AMCUST_URL', plugins_url( '/', __FILE__ ) ); // e.g. https://www.example.com/wp-content/plugins/this-plugin/
define( 'AMCUST_PATH', plugin_dir_path( __FILE__ ) ); // e.g. /home/user/apps/wp-root/wp-content/plugins/this-plugin/
// define( 'AMCUST_BASE', plugin_basename( __FILE__ ) ); // e.g. plugin-slug/this-file.php
// define( 'AMCUST_FILE', __FILE__ ); // /home/user/apps/wp-root/wp-content/plugins/this-plugin/this-file.php

// Register autoloading classes
spl_autoload_register( 'amcust_autoloader' );

/**
 * Autoload classes defined by this plugin
 *
 * @param string $class_name e.g. \AMCUST\Classes\The_Name
 * @since 1.0.0
 */
function amcust_autoloader( $class_name ) {

    $namespace = 'AMCUST';

    // Only process classes within this plugin's namespace

    if ( false !== strpos( $class_name, $namespace ) ) {

        // Assemble file path where class is defined

        // \AMCUST\Classes\The_Name => \Classes\The_Name
        $path = str_replace( $namespace, "", $class_name );

        // \Classes\The_Name => /classes/the_name
        $path = str_replace( "\\", DIRECTORY_SEPARATOR, strtolower( $path ) );

        // /classes/the_name =>  /classes/the-name.php
        $path = str_replace( "_", "-", $path ) . '.php';

        // /classes/the-name.php => /classes/class-the-name.php
        $path = str_replace( "classes" . DIRECTORY_SEPARATOR, "classes" . DIRECTORY_SEPARATOR . "class-", $path );

        // Remove first '/'
        $path = substr( $path, 1 );

        // Get /plugin-path/classes/class-the-name.php
        $path = AMCUST_PATH . $path;

        if ( file_exists( $path ) ) {
            require_once( $path );
        }                                                                       

    }

}

/**
 * Code that runs on plugin activation
 * 
 * @since 1.0.0
 */
function amcust_on_activation() {
    $activation = new AMCUST\Classes\Activation;
    $activation->activate();
}

/**
 * Code that runs on plugin deactivation
 * 
 * @since 1.0.0
 */
function amcust_on_deactivation() {
    $deactivation = new AMCUST\Classes\Deactivation;
    $deactivation->deactivate();
}

// Register code that runs on plugin activation
register_activation_hook( __FILE__, 'amcust_on_activation');

// Register code that runs on plugin deactivation
register_deactivation_hook( __FILE__, 'amcust_on_deactivation' );

// Functions for setting up admin menu, admin page, the settings sections and fields and other fondational stuff
require_once AMCUST_PATH . 'settings.php';

// Bootstrap all the functionalities of this plugin
require_once AMCUST_PATH . 'bootstrap.php';