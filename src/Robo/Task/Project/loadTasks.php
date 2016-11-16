<?php

namespace Drubo\Robo\Task\Project;

use Drubo\Robo\Task\Project\Build\CreateClone;
use Drubo\Robo\Task\Project\Config\Initialize;

trait loadTasks {

  /**
   * Create project clone.
   *
   * @return \Drubo\Robo\Task\Project\Build\CreateClone
   */
  protected function taskProjectBuildCreateClone() {
    return $this->task(CreateClone::class);
  }

  /**
   * Initialize project configuration.
   *
   * @return \Drubo\Robo\Task\Project\Config\Initialize
   */
  protected function taskProjectConfigInitialize() {
    return $this->task(Initialize::class);
  }

}
