<?php

namespace Drubo\Robo\Task\Drupal\Module;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Uninstall Drupal module(s).
 */
class Uninstall extends Exec {

  use ModuleTaskTrait;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'module:uninstall';

    foreach ($this->modules as $module) {
      $args[] = $module;
    }

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Uninstalling Drupal module(s)';
  }

}
