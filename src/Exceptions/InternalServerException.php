<?php

namespace HC\RestRoutes\Exceptions;

class InternalServerException extends RestfulException
{
  protected function getStatusCode()
  {
    return 500;
  }
}
