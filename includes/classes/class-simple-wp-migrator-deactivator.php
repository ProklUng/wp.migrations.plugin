<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * 
 * @since      1.0.0
 *
 * @package    Simple Wp Migrator
 * @subpackage Simple Wp MigratorPlugin_Name/includes
 *      
 */
class SimpleWpMigrator_Deactivator {

    /**
     * @var string $prefix
     */
    private static $prefix = 'simple_wp_migrator_';

    /**
     * @var string[] $options Options for cleaning.
     */
    private static $options = [
        'migration_path',
    ];

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        foreach (static::$options as $option) {
            delete_option(static::$prefix . $option);
        }

        // Clear all another plugin options
        $all_options = wp_load_alloptions();
        foreach ($all_options as $name => $value ) {
            if ( stripos( $name, static::$prefix ) !== false) {
                delete_option($name);
            }
        }
	}
}
