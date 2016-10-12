<?php

namespace Drubo\Exception;

class DisabledCommandException extends \Exception {

  /**
   * Constructor.
   *
   * @param string $commandName
   *   A console command name.
   */
  public function __construct($commandName) {
    parent::__construct('Disabled command: ' . $commandName);
  }

}
