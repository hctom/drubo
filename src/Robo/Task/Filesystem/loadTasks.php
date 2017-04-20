<?php

namespace Drubo\Robo\Task\Filesystem;

use Drubo\Robo\Task\Filesystem\Clean\Directories as CleanDirectories;
use Drubo\Robo\Task\Filesystem\Prepare\Directories as PrepareDicrectories;
use Drubo\Robo\Task\Filesystem\Prepare\Files;

trait loadTasks {

  /**
   * Clean filesystem directories.
   *
   * @return \Drubo\Robo\Task\Filesystem\Clean\Directories
   */
  protected function taskFilesystemCleanDirectories() {
    return $this->task(CleanDirectories::class);
  }

  /**
   * Prepare filesystem directories.
   *
   * @return \Drubo\Robo\Task\Filesystem\Prepare\Directories
   */
  protected function taskFilesystemPrepareDirectories() {
    return $this->task(PrepareDicrectories::class);
  }

  /**
   * Prepare filesystem files.
   *
   * @return \Drubo\Robo\Task\Filesystem\Prepare\Files
   */
  protected function taskFilesystemPrepareFiles() {
    return $this->task(Files::class);
  }

}
