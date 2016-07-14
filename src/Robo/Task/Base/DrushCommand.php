<?php

namespace Drubo\Robo\Task\Base;

/**
 * Robo task base class for tasks utilizing Drush.
 */
abstract class DrushCommand extends EncapsulatedExec {

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->config()
      ->get('drush.path');
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->config()
      ->get('docroot.path');
  }

}
