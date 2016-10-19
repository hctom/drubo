<?php

namespace Drubo\Robo\Task\Filesystem;

trait loadTasks {

  /**
   * Prepare filesystem directories.
   *
   * @return PrepareDirectories
   */
  protected function taskPrepareFilesystemDirectories() {
    return $this->task(PrepareDirectories::class);
  }

}
