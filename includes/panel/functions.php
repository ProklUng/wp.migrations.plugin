<?php


if ( ! defined( 'WPINC' ) ) die;

if (!function_exists('wp_create_admin_page')) {
    function wp_create_admin_page ( $settings ) {
        return new _WP_Admin_Page( $settings );
    }
}

if (!function_exists('wp_get_admin_page')) {
    function wp_get_admin_page ( $page_id = '' ) {
        return _WP_Admin_Page::get_instance_by_id( $page_id );
    }
}

if (!function_exists('wp_get_page_field_value')) {
    function wp_get_page_field_value ( $page_id, $field_id ) {
        $the_page = _WP_Admin_Page::get_instance_by_id( $page_id );

        if ( ! empty( $the_page ) ) {
            return $the_page->get_field_value( $field_id );
        }

        return false;
    }
}

