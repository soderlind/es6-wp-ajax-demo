<?php
/**
 * ES6 WP Ajax Demo
 *
 * @package     ES6_WP_AJAX_DEMO
 * @author      Per Soderlind
 * @copyright   2019 Per Soderlind
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: ES6 WP Ajax Demo
 * Plugin URI: https://github.com/soderlind/es6-wp-ajax-demo
 * GitHub Plugin URI: https://github.com/soderlind/es6-wp-ajax-demo
 * Description: Use native JavaScript (ES6) when doing Ajax calls.
 * Version:     2.0.2
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * Text Domain: es6-wp-ajax-demo
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

declare( strict_types = 1 );
namespace Soderlind\Demo\Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ES6_WP_AJAX_DEMO_VERSION = '2.0.0';

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wp_scripts' ); 
add_action( 'wp_ajax_es6_ajax_action', __NAMESPACE__ . '\es6_ajax_action' ); //Tell WordPress which function should handle the AJAX call.

/**
 * Ajax action, triggerd by fetch() in es6-wp-ajax-demo.js
 *
 * @return void
 */
function es6_ajax_action() {
	header( 'Content-type: application/json' ); // The response is in JSON format, so set the correct header
	if ( check_ajax_referer( 'es6_wp_ajax_nonce', 'nonce', false ) ) {
		$sum = ( isset( $_POST['sum'] ) ) ? filter_var( wp_unslash( $_POST['sum'] ), FILTER_VALIDATE_INT, [ 'default' => 0 ] ) : 0;
		if ( isset( $sum ) ) {
			$response['response'] = 'success';
			$sum                  = ++$sum;
			$response['data']     = $sum;
			update_option( 'es6demo_sum', $sum );
		} else {
			$response['response'] = 'failed';
			$response['data']     = 'something went wrong ...';
		}
	} else {
		$response['response'] = 'failed';
		$response['message']  = 'invalid nonse';
	}
	echo wp_json_encode( $response );
	wp_die();
}

/**
 * Add Scripts.
 *
 * @return void
 */
function wp_scripts() {
	$ajaxurl = get_ajax_url();
	$url     = plugins_url( '', __FILE__ );

	// Load fetch polyfill, url via https://polyfill.io/v3/url-builder/.
	wp_enqueue_script( 'polyfill-fetch', 'https://polyfill.io/v3/polyfill.min.js?features=fetch', [], ES6_WP_AJAX_DEMO_VERSION, true );
	wp_enqueue_script( 'es6-wp-ajax', $url . '/es6-wp-ajax-demo.js', [ 'polyfill-fetch' ], ES6_WP_AJAX_DEMO_VERSION, true );
	$data = wp_json_encode(
		[
			'nonce'   => wp_create_nonce( 'es6_wp_ajax_nonce' ),
			'ajaxurl' => $ajaxurl,
		]
	);
	wp_add_inline_script( 'es6-wp-ajax', "const pluginES6WPAjax = ${data};" );
}

/**
 * Get the Ajax URL.
 *
 * @return string
 */
function get_ajax_url() : string {
	// multisite fix, use home_url() if domain mapped to avoid cross-domain issues.
	$http_scheme = ( is_ssl() ) ? 'https' : 'http';
	if ( home_url() !== site_url() ) {
		$ajaxurl = home_url( '/wp-admin/admin-ajax.php', $http_scheme );
	} else {
		$ajaxurl = site_url( '/wp-admin/admin-ajax.php', $http_scheme );
	}
	return $ajaxurl;
}

/**
 * Demo Form
 */
add_shortcode( 'es6demo', __NAMESPACE__ . '\es6demo_form' );

/**
 * Create Demo Form.
 *
 * @param array $args Shortcode arguments.
 * @return string
 */
function es6demo_form( $args ) {
	$o   = '';
	$sum = get_option( 'es6demo_sum', 0 );
	$o  .= '<div id="es6-demo">';
	$o  .= '<div id="es6-demo-output">' . $sum . '</div>';
	$o  .= '<form><input id="es6-demo-input" type="button" value="+" data-sum="' . $sum . '"></form>';
	$o  .= '</div>';

	return $o;
}

add_action(
	'wp_enqueue_scripts',
	function() {
		$url = plugins_url( '', __FILE__ );
		wp_enqueue_style( 'es6-wp-form', $url . '/es6-wp-ajax-demo.css', [], ES6_WP_AJAX_DEMO_VERSION );
	}
);

