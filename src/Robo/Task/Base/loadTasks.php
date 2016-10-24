<?php

namespace Drubo\Robo\Task\Base;

trait loadTasks {

  /**
   * Generate a textual representation of the differences.
   *
   * @return Diff
   */
  protected function taskDiff() {
    return $this->task(Diff::class);
  }

}
