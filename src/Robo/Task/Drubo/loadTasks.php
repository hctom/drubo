<?php

namespace Drubo\Robo\Task\Drubo;

trait loadTasks {

  /**
   * Initialize Drubo.
   *
   * @return Initialize
   */
  protected function taskInitializDrubo() {
    return $this->task(Initialize::class);
  }

}
