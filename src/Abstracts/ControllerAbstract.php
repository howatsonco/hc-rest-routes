<?php

namespace HC\RestRoutes\Abstracts;

use HC\RestRoutes\Exceptions\ForbiddenException;

/**
 * Rest controller.
 */
abstract class ControllerAbstract
{
  /**
   * API request body.
   */
  protected $request = array();

  protected $response = null;

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
  private function getRequestBody()
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
  protected function validateAdmin()
  {
    if (!(current_user_can('manage_options') || is_admin() || is_super_admin())) {
      throw new ForbiddenException("Unauthorized");
    }
  }

  /**
   * Validate if current user is an editor
   */
  protected function validateEditor()
  {
    if (!(current_user_can('editor') || current_user_can('manage_options'))) {
      throw new ForbiddenException("Unauthorized");
    }
  }
}
