<?php

namespace Drubo\Robo\Task\Project;

trait loadTasks {

  /**
   * Initialize Drubo.
   *
   * @return InitializeConfig
   */
  protected function taskInitializeProjectConfig() {
    return $this->task(InitializeConfig::class);
  }

}
