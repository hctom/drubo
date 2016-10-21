<?php

namespace Drubo\Robo\Task\DrupalConsole;

use Drubo\Robo\Task\Base\EncapsulatedExec;

/**
 * Robo task base class for Drush execution tasks.
 */
abstract class Exec extends EncapsulatedExec {

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('drush.path');
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('filesystem.directories.docroot.path');
  }

}
