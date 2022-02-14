<?php

namespace HC\RestRoutes\RestApi\Controllers;

/**
 * Rest controller.
 */
class Controller
{
  /**
   * API request body.
   */
  protected $request = array();

  /**
   * Handle Controller setup.
   */
  public function __construct()
  {
    $this->request = $this->getRequestBody();
  }

  /**
   * Retrieves request body in object format.
   * @return object
   */
  public static function getRequestBody()
  {
    $request = $_REQUEST;

    if (isset($_SERVER["CONTENT_TYPE"]) && stripos($_SERVER["CONTENT_TYPE"], "application/json") > -1) {
      $rawJson = file_get_contents("php://input");

      try {
        $jsonObject = json_decode($rawJson, true);

        foreach ($jsonObject as $key => $value) {
          $request[$key] = $value;
        }
      } catch (\Exception $ex) {
      }
    }

    return $request;
  }

  /**
   * Allow CORS.
   */
  public static function allowCors()
  {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
  }

  /**
   * Set cache control headers.
   */
  public static function setCacheControl($ttl = 300)
  {
    header('Cache-Control: public, max-age=' . $ttl);
  }

  /**
   * Get Wordpress's Database query object
   * @return wpdb
   */
  public static function getWpQuery()
  {
    global $wpdb;
    /* @var $wpdb wpdb */
    return $wpdb;
  }

  /**
   * Render an object as json response, and optionally end the execution of the script
   * @param $object
   * @param bool $end_execution
   */
  public static function toJsonResponse($object, $http_code = \Noshu\Constants::HTTP_STATUS_OK, $end_execution = true)
  {
    http_response_code($http_code);
    header("Content-type: application/json");
    echo json_encode($object);
    if ($end_execution) {
      exit;
    }
  }

  /**
   * Validate if current user is admin
   */
  public function validateAdmin()
  {
    if (!current_user_can('manage_options') && (is_admin() || is_super_admin())) {
      header('HTTP/1.0 403 Forbidden');
      $this->toJsonResponse(array(
        "success" => false,
        "message" => "Unauthorized",
      ));
      exit;
    }
  }

  /**
   * Validate if current user is an editor
   */
  public function validateEditor()
  {
    if (!(current_user_can('editor') || current_user_can('manage_options'))) {
      header('HTTP/1.0 403 Forbidden');
      $this->toJsonResponse(array(
        "success" => false,
        "message" => "Unauthorized",
      ));
      exit;
    }
  }

  /**
   * Example API endpoint
   * 
   * The function name should be split into two parts: ${name}_${httpVerb}.
   * 
   * You can use the utility function $this->toJsonResponse to format the response as JSON.
   *
   * public function example_get($region)
   * {
   *   $query = new \WP_Query([
   *     'post_status' => 'publish',
   *     'post_type' => array('post'),
   *   ]);
   * 
   *   return $this->toJsonResponse(array(
   *     'region' => $region,
   *     'results' => $query->posts,
   *   ));
   * }
   */
}
