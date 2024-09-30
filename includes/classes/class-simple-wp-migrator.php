<?php

/**
 * The core plugin class.
 *
 * This is used to define attributes, functions, internationalization used across
 * both the admin-specific hooks, and public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * 
 * @since      1.0.0
 *
 * @package    Simple Wp Migrator
 * @subpackage Simple Wp MigratorPlugin_Name/includes
 *      
 */
class SimpleWpMigrator {

	/**
	 * The loader that's responsible for maintaining and registering all hooks
	 * that power the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SimpleWpMigrator_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        $this->version = '1.0.0';
		if ( defined( 'SIMPLE_WP_MIGRATOR_VERSION' ) ) {
			$this->version = SIMPLE_WP_MIGRATOR_VERSION;
		}
        
		$this->plugin_name = 'simple-wp-migrator';

		$this->load_dependencies();
		$this->panel();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SimpleWpMigrator_Loader. Orchestrates the hooks of the plugin.
	 * - SimpleWpMigrator_i18n. Defines internationalization functionality.
	 * - SimpleWpMigrator_Admin. Defines all hooks for the admin area.
	 * - SimpleWpMigrator_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-simple-wp-migrator-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-simple-wp-migrator-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-simple-wp-migrator-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-simple-wp-migrator-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-simple-wp-migrator-register-post-types.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-simple-wp-migrator-register-taxonomies.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/class-simple-wp-migrator-ajax.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'panel/init.php';

        $base_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'libs/';

		spl_autoload_register( function ( $class ) use( $base_dir ) {
            if (class_exists($class) || interface_exists($class) || trait_exists($class)) {
                return;
            }

            $parts = explode('\\', $class);

            $relative_class = array_pop($parts);
            $file = $base_dir . str_replace('\\', '/', $class) . '.php';

            $file = str_replace('Arrilot', 'arrilot', $file);

            if (file_exists($file)) {
                include_once $file;
            }

        } );

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/tools/class-simple-wp-translit.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/tools/class-wordpress-posts-repository.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'classes/tools/class-wordpress-menu-repository.php';

		$this->loader = new SimpleWpMigrator_Loader();
	}

    /**
     * Админ-панель с опциями.
     *
     * @return void
     * @throws Exception
     */
    private function panel() : void
    {
        $configPage = wp_create_admin_page([
            'menu_name' => __('Мигратор', 'simple-wp-migrator'),
            'id' => 'simple-wp-migrator-settings',
            'prefix' => 'simple_wp_migrator_',
        ]);

        $settings = include_once plugin_dir_path( dirname( __FILE__ ) ) . 'settings/settings.php';
        foreach ($settings as $setting) {
            $configPage->add_field( $setting );
        }
    }

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SimpleWpMigrator_I18n class in order to set the domain and to
	 * register the hook with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SimpleWpMigrator_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new SimpleWpMigrator_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->ajaxHandlers();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SimpleWpMigrator_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

    /**
     * @return void
     */
    private function ajaxHandlers() : void
    {
        add_action('wp_ajax_create_migration', [ new SimpleWpMigratorAjax, 'create_migration' ] );
        add_action('wp_ajax_nopriv_create_migration', [ new SimpleWpMigratorAjax, 'create_migration' ] );

        add_action('wp_ajax_run_migrations', [ new SimpleWpMigratorAjax, 'run_migrations' ] );
        add_action('wp_ajax_nopriv_run_migrations', [ new SimpleWpMigratorAjax, 'run_migrations' ] );

        add_action('wp_ajax_rollback_migration', [ new SimpleWpMigratorAjax, 'rollback_migration' ] );
        add_action('wp_ajax_nopriv_rollback_migration', [ new SimpleWpMigratorAjax, 'rollback_migration' ] );
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    SimpleWpMigrator_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
