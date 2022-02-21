<?php

namespace HC\RestRoutes;

use HC\RestRoutes\Traits\SingletonTrait;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * The core plugin class.
 */
final class Server
{
	use SingletonTrait;

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
	public $version = "1.0.1";

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct()
	{
		$this->loadRestApi();
		do_action('hcrr_init');
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
