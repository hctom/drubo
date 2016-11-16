<?php

namespace Drubo\Robo\Task\Base;

trait loadTasks {

  /**
   * Ask for confirmation.
   *
   * @return ConfirmationQuestion
   */
  protected function taskConfirmationQuestion() {
    return $this->task(ConfirmationQuestion::class);
  }

  /**
   * Generate a textual representation of the differences.
   *
   * @return Diff
   */
  protected function taskDiff() {
    return $this->task(Diff::class);
  }

}
