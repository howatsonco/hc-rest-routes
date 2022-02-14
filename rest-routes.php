<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              howatsonco.com.au
 * @since             1.0.0
 * @package           rest-routes
 *
 * @wordpress-plugin
 * Plugin Name:       rest-routes
 * Plugin URI:        rest-routes
 * Description:       H+C Custom REST API router
 * Version:           1.0.0
 * Author:            Howatsonco
 * Author URI:        howatsonco.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rest-routes
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Define HCRR_PLUGIN_FILE.
if (!defined('HCRR_PLUGIN_FILE')) {
	define('HCRR_PLUGIN_FILE', __FILE__);
}

require dirname( __FILE__ ) . '/vendor/autoload.php';

// Include the main class.
if (!class_exists('RestRoutes')) {
  include_once dirname(__FILE__) . '/src/includes/RestRoutes.php';
}

/**
 * Returns the main instance of HCRR to prevent the need to use globals.
 *
 * @return RestRoutes
 */
function HCRR()
{
  return HC\RestRoutes\RestRoutes::instance();
}

// Global for backwards compatibility.
$GLOBALS['hc-rest-routes'] = HCRR();
