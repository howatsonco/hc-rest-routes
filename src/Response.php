<?php

namespace HC\RestRoutes;

/**
 * Rest response.
 */
class Response extends \WP_HTTP_Response
{
  /**
   * Allow CORS.
   */
  public function allowCors($origin = "*") 
  {
    $this->headers[] = "Access-Control-Allow-Origin: " . $origin;
    $this->headers[] = "Access-Control-Allow-Credentials: true";
    $this->headers[] = "Access-Control-Max-Age: 86400";
    $this->headers[] = "Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS";
  }

  /**
   * Set cache control headers.
   */
  public function setCacheControl($ttl = 300)
  {
    $this->headers[] = "Cache-Control: public, max-age=" . $ttl;
  }
}
