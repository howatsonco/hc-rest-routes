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
 * @package           hc-rest-routes
 *
 * @wordpress-plugin
 * Plugin Name:       H+C Rest Routes
 * Plugin URI:        hc-rest-routes
 * Description:       H+C Custom REST API router
 * Version:           1.0.0
 * Author:            Howatsonco
 * Author URI:        howatsonco.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hc-rest-routes
 */

use HC\RestRoutes\Server;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Define HCRR_PLUGIN_FILE.
if (!defined('HCRR_PLUGIN_FILE')) {
	define('HCRR_PLUGIN_FILE', __FILE__);
}

require dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Returns the main instance of HCRR to prevent the need to use globals.
 *
 * @return RestRoutes
 */
function HCRR()
{
  return Server::instance();
}

// Global for backwards compatibility.
$GLOBALS['hcrr'] = HCRR();