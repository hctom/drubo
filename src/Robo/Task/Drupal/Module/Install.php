<?php

namespace Drubo\Robo\Task\Drupal\Module;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Install Drupal module(s).
 */
class Install extends Exec {

  use ModuleTaskTrait;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'module:install';

    foreach ($this->modules as $module) {
      $args[] = $module;
    }

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Installing Drupal module(s)';
  }

}
