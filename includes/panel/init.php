<?php

if ( ! defined( 'WPINC' ) ) die;

if ( ! defined( 'BETTER_WP_ADMIN_API_VERSION' ) ) {

	define( 'BETTER_WP_ADMIN_API_VERSION', '0.6.0' );
	define( 'BETTER_WP_ADMIN_API_DIR', __DIR__ );
	define( 'BETTER_WP_ADMIN_API_FILE', __FILE__ );

	if ( ! class_exists(Parsedown::class) ) {
		require_once( __DIR__ . '/libs/Parsedown/Parsedown.php' );
	}

    if (!class_exists(_WP_Field_Renderer::class)) {
        require_once( __DIR__ . '/classes/class-wp-field-renderer.php' );
    }

    if (!class_exists(_WP_Admin_Page::class)) {
        require_once( __DIR__ . '/classes/class-wp-admin-page.php' );
    }

	require_once( __DIR__ . '/functions.php' );
}
