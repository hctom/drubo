<?php

namespace Drubo\Robo\Task\Project;

use Drubo\Robo\Task\Project\Config\Initialize;

trait loadTasks {

  /**
   * Initialize project configuration.
   *
   * @return \Drubo\Robo\Task\Project\Config\Initialize
   */
  protected function taskInitializeProjectConfig() {
    return $this->task(Initialize::class);
  }

}
