<?php

namespace HC\RestRoutes\RestApi;

use HC\RestRoutes\RestApi\Factory\ControllerFactory;
use HC\RestRoutes\SingletonTrait;

/**
 * Rest API request routing.
 */
class Router
{
  use SingletonTrait;

  /**
   * API prefix.
   */
  public $prefix = '/api/hcrr';

  /**
   * List of valid API routes.
   */
  public $routes = null;

  /**
	 * Controller factory instance.
	 *
	 * @var ControllerFactory
	 */
	public $controllerFactory = null;

  /**
   * Router constructor.
   */
  public function __construct()
  {
    $this->controllerFactory = new ControllerFactory();
    $this->routes = apply_filters( 'hcrr_routes', $this->routes );
    $this->prefix = apply_filters( 'hcrr_prefix', $this->prefix );
    add_action( 'parse_request', array($this, 'processRequest'), 1 );
    do_action( 'hcrr_router_init', $this );
  }

  /**
   * Returns API prefix.
   * @return string
   */
  public function getPrefix()
  {
    return (defined('WP_SUBDIRECTORY') ? WP_SUBDIRECTORY : '') . self::$prefix;
  }

  /**
   * Returns request URI.
   * @return string
   */
  public function getUri()
  {
    return str_replace('//', '/', $_SERVER['REQUEST_URI']);
  }

  /**
   * Process API request from client.
   */
  public function processRequest()
  {
    $uri = $this->getUri();
    $prefix = $this->getPrefix();

    if (Utils::startWith($uri, $prefix, false)) {
      $uri = str_replace($prefix, '', $uri);

      foreach (self::$routes as $pattern => $route) {
        $matches = array();

        if (preg_match('#^' . $pattern . '/?(\?.*)?$#', $uri, $matches)) {
          array_shift($matches);
          $this->executeAction($route, $matches);
        }
      }
    }
  }

  /**
   * Executes API action associated with route.
   */
  public function executeAction($route, $args = array())
  {
    $controller = $this->loadController($route['controller']);

    if ($controller) {
      $action = (isset($route['action']) ? $route['action'] : 'index') . '_' . strtolower($_SERVER['REQUEST_METHOD']);
      call_user_func_array(array($controller, $action), $args);
      if (!isset($route['continue']) || !filter_var($route['continue'], FILTER_VALIDATE_BOOLEAN)) {
        exit();
      }
    }
  }

  /**
   * Loads REST API Controller associated with route.
   */
  public function loadController($id)
  {
    return $this->controllerFactory->getController($id);
  }
}
