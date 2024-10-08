<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * 
 * @since      1.0.0
 *
 * @package    Simple Wp Migrator
 * @subpackage Simple Wp MigratorPlugin_Name/includes
 *      
 */
class SimpleWpMigrator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, WP_PLUGIN_URL  . '/simple-wp-migrator/admin/css/migrator-admin.css', array(),
            @filemtime( WP_CONTENT_DIR  . '/plugins/simple-wp-migrator/admin/css/migrator-admin.css' ), 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name . '_preloader', WP_PLUGIN_URL  . '/simple-wp-migrator/admin/js/preloader.js',
            array( 'jquery'), @filemtime( WP_CONTENT_DIR  . '/plugins/simple-wp-migrator/admin/js/preloader.js' ), false );

        wp_enqueue_script( $this->plugin_name . '_admin', WP_PLUGIN_URL  . '/simple-wp-migrator/admin/js/migrator-admin.js',
            array( 'jquery'), @filemtime( WP_CONTENT_DIR  . '/plugins/simple-wp-migrator/admin/js/migrator-admin.js' ), false );
	}

}
