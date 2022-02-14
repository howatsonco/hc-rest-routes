<?php

namespace HC\RestRoutes\RestApi;

/**
 * Rest API utility methods.
 */
class Utils
{
  /**
   * Check if $needle is at the beginning of $haystack string
   * @param string $haystack Base string to find $needle string from
   * @param string $needle String to test for
   * @param bool $caseSensitive Determine if the function should match case sensitive keyword (default=True)
   * @return bool
   */
  public static function startWith($haystack, $needle, $caseSensitive = true)
  {
    return strncmp($caseSensitive ? $haystack : strtolower($haystack), $caseSensitive ? $needle : strtolower($needle), strlen($needle)) == 0;
  }

  /**
   * Render an object as json response, and optionally end the execution of the script
   * @param $object
   * @param bool $end_execution
   */
  public static function toJsonResponse($object, $http_code = Constants::HTTP_STATUS_OK, $end_execution = true)
  {
    http_response_code($http_code);
    header("Content-type: application/json");
    echo json_encode($object);
    if ($end_execution) {
      exit;
    }
  }
}
