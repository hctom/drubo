<?php

namespace Drubo\Exception;

class InvalidEnvironmentException extends \Exception {

  /**
   * Constructor.
   *
   * @param string $environment
   *   An environment identifier.
   */
  public function __construct($environment) {
    parent::__construct('Invalid environment: ' . $environment);
  }

}
