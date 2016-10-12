<?php

namespace Drubo\Exception;

class EnvironmentAwareCommandException extends \Exception {

  /**
   * Constructor.
   *
   * @param string $commandName
   *   A console command name.
   */
  public function __construct($commandName) {
    parent::__construct('Command requires environment: ' . $commandName);
  }

}
