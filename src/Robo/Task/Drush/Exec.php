<?php

namespace Drubo\Robo\Task\Drush;

use Drubo\Robo\Task\Base\EncapsulatedExec;

/**
 * Robo task base class for Drush execution tasks.
 */
abstract class Exec extends EncapsulatedExec {

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->environmentConfig()
      ->get('drush.path');
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->environmentConfig()
      ->get('filesystem.directories.docroot.path');
  }

}
