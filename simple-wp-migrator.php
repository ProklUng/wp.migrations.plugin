<?php

/**
 * The plugin bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://yoursite.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Wp Migrator
 * Plugin URI:        http://yoursite.com
 * Description:       Простые миграции для Wordpress.
 * Version:           1.0.0
 * Author:            Your Company
 * Author URI:        http://yoursite.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-wp-migrator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version. Start at version 1.0.0
 * For the versioning of the plugin is used SemVer - https://semver.org
 * Rename this for every new plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/classes/class-simple-wp-migrator-activator.php
 */
function my_activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class-simple-wp-migrator-activator.php';
	SimpleWpMigrator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/classes/class-simple-wp-migrator-deactivator.php
 */
function my_deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class-simple-wp-migrator-deactivator.php';
	SimpleWpMigrator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'my_activate_plugin_name' );
register_deactivation_hook( __FILE__, 'my_deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/classes/class-simple-wp-migrator.php';

/**
 * The plugin functions file that is used to define general functions, shortcodes etc.
 */
require plugin_dir_path( __FILE__ ) . 'includes/functions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function my_run_simple_wp_migrator() {
	$plugin = new SimpleWpMigrator();
	$plugin->run();
}

my_run_simple_wp_migrator();
