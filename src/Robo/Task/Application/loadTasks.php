<?php

namespace Drubo\Robo\Task\Application;

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
