<?php

namespace HC\RestRoutes;

use HC\RestRoutes\Traits\Singleton;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * The core plugin class.
 */
final class Server
{
	use Singleton;

	/**
	 * Router instance.
	 */
	public $router;

	/**
	 * Response instance.
	 */
	public $response;

	/**
	 * The current version of the plugin.
	 *
	 * @var string
	 */
	public $version = "1.0.0";

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct()
	{
		$this->loadRestApi();
		do_action('hcrr_init');
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function pluginUrl()
	{
		return untrailingslashit(plugins_url('/', HCRR_PLUGIN_FILE));
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function pluginPath()
	{
		return untrailingslashit(plugin_dir_path(HCRR_PLUGIN_FILE));
	}

	/**
	 * Get the absolute plugin path.
	 *
	 * @return string
	 */
	public function pluginPathAbs()
	{
		return untrailingslashit(dirname(HCRR_PLUGIN_FILE) . '/');
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define($name, $value)
	{
		if (!defined($name)) {
			define($name, $value);
		}
	}

	/**
	 * Load REST API.
	 */
	public function loadRestApi()
	{
		$this->router = Router::instance();
	}

	/**
	 * Handles serving a REST API request.
	 */
	public static function serveRequest(Response $response)
	{
		status_header($response->status);

		header("Content-type: application/json");

		foreach ($response->headers as $value) {
			header($value);
		}
		
		echo wp_json_encode($response->data);

		exit;
	}
}
