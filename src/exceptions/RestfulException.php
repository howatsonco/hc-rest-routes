<?php

namespace HC\RestRoutes\Exceptions;

use HC\RestRoutes\Response;
use HC\RestRoutes\Server;

/**
 * Restful Exception class.
 */
abstract class RestfulException extends \Exception
{
  /**
   * Error payload.
   */
  protected $payload;

  /**
   * Constructor.
   */
  public function __construct($payload)
  {
    $this->payload = $payload;
  }

  /**
   * Returns status code.
   */
  abstract protected function getStatusCode();

  /**
   * Output error.
   */
  public function outputError()
  {
    return Server::serveRequest(
      new Response(
        array(
          "success" => false,
          "data" => $this->payload
        ),
        $this->getStatusCode()
      )
    );
  }
}
