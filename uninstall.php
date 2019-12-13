<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://github.com/soderlind/es6-wp-ajax-demo
 * @since      2.0.0
 * @package    ES6_WP_AJAX_DEMO
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'es6demo_sum' );
