<?php

namespace HC\RestRoutes;

use HC\RestRoutes\Factories\ControllerFactory;
use HC\RestRoutes\Traits\Singleton;

/**
 * Rest API request routing.
 */
class Router
{
  use Singleton;

  /**
   * API prefix.
   */
  public $prefix = '/api/hcrr';

  /**
   * List of valid API routes.
   */
  public $routes = array();

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
    $this->routes = apply_filters('hcrr_routes', $this->routes);
    $this->prefix = apply_filters('hcrr_prefix', $this->prefix);
    add_action('parse_request', array($this, 'processRequest'), 1);
    do_action('hcrr_router_init', $this);
  }

  /**
   * Unregister a route.
   * 
   * @param string      $pattern  Pattern for route to match.
   */
  public function setPrefix($prefix)
  {
    $this->prefix = $prefix;
  }

  /**
   * Register all routes.
   *
   * @param array      $routes  List of routes to set.
   */
  public function registerRoutes($routes)
  {
    $this->routes = $routes;
  }

  /**
   * Register a route.
   *
   * @param string      $pattern  Pattern for route to match.
   * @param string      $controller Key of controller to access.
   * @param string      $action Name of controller method to invoke.
   */
  public function registerRoute($pattern, $controller, $action)
  {
    $this->routes[$pattern] = array(
      'controller' => $controller,
      'action' => $action
    );
  }

  /**
   * Unregister a route.
   * 
   * @param string      $pattern  Pattern for route to match.
   */
  public function unregisterRoute($pattern)
  {
    unset($this->routes[$pattern]);
  }

  /**
   * Process API request from client.
   */
  public function processRequest()
  {
    $uri = str_replace('//', '/', $_SERVER['REQUEST_URI']);
    $prefix = (defined('WP_SUBDIRECTORY') ? WP_SUBDIRECTORY : '') . $this->prefix;

    if (Utils::startWith($uri, $prefix, false)) {
      $uri = str_replace($prefix, '', $uri);

      foreach ($this->routes as $pattern => $route) {
        $matches = array();

        if (preg_match('#^' . $pattern . '/?(\?.*)?$#', $uri, $matches)) {
          array_shift($matches);
          $this->executeAction($route, $matches);
        }
      }
    }

    // If we haven't sent a response by this point,
    // assume that the API endpoint doesn't exist.
    Server::serveRequest(
      new Response(
        array(
          "success" => false,
          "message" => "Endpoint does not exist",
        ),
        Constants::HTTP_STATUS_NOT_FOUND
      )
    );
  }

  /**
   * Returns controller instance for route.
   */
  public function getController($route)
  {
    return $this->controllerFactory->getController($route['controller']);
  }

  /**
   * Returns action callback for route.
   */
  public function getAction($route)
  {
    return (isset($route['action']) ? $route['action'] : 'index') . '_' . strtolower($_SERVER['REQUEST_METHOD']);
  }

  /**
   * Executes API action associated with route.
   */
  public function executeAction($route, $args = array())
  {
    $controller = $this->getController($route);
    $action = $this->getAction($route);

    if (!$controller) {
      Server::serveRequest(
        new Response(
          array(
            "success" => false,
            "message" => "Controller does not exist",
          ),
          Constants::HTTP_STATUS_NOT_FOUND
        )
      );
    }

    if (!$action) {
      Server::serveRequest(
        new Response(
          array(
            "success" => false,
            "message" => "Action does not exist",
          ),
          Constants::HTTP_STATUS_NOT_FOUND
        )
      );
    }

    call_user_func_array(
      array($controller, $action),
      $args
    );
  }
}
