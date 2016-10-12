<?php

namespace Drubo\Exception;

/**
 * Exception: Console command that needs environment context to be set.
 */
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
