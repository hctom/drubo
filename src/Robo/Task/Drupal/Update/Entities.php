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
  protected function title() {
    return 'Applying Drupal entity schema updates';
  }

}
