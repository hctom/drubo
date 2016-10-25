<?php

namespace Drubo\Robo\Task\Project;

trait loadTasks {

  /**
   * Initialize drubo.
   *
   * @return InitializeConfig
   */
  protected function taskInitializeProjectConfig() {
    return $this->task(InitializeConfig::class);
  }

}
