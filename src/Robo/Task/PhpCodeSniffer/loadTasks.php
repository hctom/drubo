<?php

namespace Drubo\Robo\Task\PhpCodeSniffer;

use Drubo\Robo\Task\PhpCodeSniffer\Run;

trait loadTasks {

  /**
   * Run PHP_CodeSniffer against code.
   *
   * @return \Drubo\Robo\Task\PhpCodeSniffer\Run
   */
  protected function taskPhpCodeSnifferRun() {
    return $this->task(Run::class);
  }

}
