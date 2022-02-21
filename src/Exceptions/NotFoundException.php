<?php

namespace HC\RestRoutes\Exceptions;

class NotFoundException extends RestfulException
{
  protected function getStatusCode()
  {
    return 404;
  }
}
