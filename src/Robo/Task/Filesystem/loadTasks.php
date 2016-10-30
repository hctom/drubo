<?php

namespace Drubo\Robo\Task\Filesystem;

use Drubo\Robo\Task\Filesystem\Prepare\Directories;
use Drubo\Robo\Task\Filesystem\Prepare\Files;

trait loadTasks {

  /**
   * Prepare filesystem directories.
   *
   * @return \Drubo\Robo\Task\Filesystem\Prepare\Directories
   */
  protected function taskFilesystemPrepareDirectories() {
    return $this->task(Directories::class);
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
