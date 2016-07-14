<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\Base\DrupalConsoleCommand;

/**
 * Robo task: Import configuration to current Drupal site.
 */
class ImportConfig extends DrupalConsoleCommand {

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'config:import';

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Importing Drupal configuration');

    return parent::run();
  }

}
