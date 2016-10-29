<?php

namespace Drubo\Exception;

/**
 * Exception: Invalid environment name.
 */
class InvalidEnvironmentException extends \Exception {

  /**
   * Constructor.
   *
   * @param string $environmentName
   *   An environment name.
   */
  public function __construct($environmentName) {
    parent::__construct('Invalid environment: ' . $environmentName);
  }

}
