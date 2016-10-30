<?php

namespace Drubo\Robo\Task\Drupal\Update;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Apply Drupal entity schema updates.
 */
class Entities extends Exec {

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'update:entities';

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Applying Drupal entity schema updates');

    return parent::run();
  }

}