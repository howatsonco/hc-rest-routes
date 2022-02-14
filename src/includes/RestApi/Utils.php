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
}
