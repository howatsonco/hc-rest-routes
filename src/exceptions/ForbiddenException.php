<?php

namespace HC\RestRoutes\Exceptions;

class ForbiddenException extends RestfulException
{
  protected function getStatusCode()
  {
    return 403;
  }
}
