<?php

namespace HC\RestRoutes;

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
   * Validate if current user is admin
   */
  public function validateAdmin()
  {
    if (!(current_user_can('manage_options') || is_admin() || is_super_admin())) {
      Server::serveRequest(
        new Response(
          array(
            "success" => false,
            "message" => "Unauthorized",
          ),
          Constants::HTTP_STATUS_FORBIDDEN
        )
      );
    }
  }

  /**
   * Validate if current user is an editor
   */
  public function validateEditor()
  {
    if (!(current_user_can('editor') || current_user_can('manage_options'))) {
      Server::serveRequest(
        new Response(
          array(
            "success" => false,
            "message" => "Unauthorized",
          ),
          Constants::HTTP_STATUS_FORBIDDEN
        )
      );
    }
  }
}
