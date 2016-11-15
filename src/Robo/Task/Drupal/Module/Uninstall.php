<?php

namespace Drubo\Robo\Task\Drupal\Module;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Exception\TaskException;

/**
 * Robo task: Uninstall Drupal module(s).
 */
class Uninstall extends Exec {

  use ModuleTrait;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'module:uninstall';

    if (empty($this->modules)) {
      throw new TaskException($this, 'No module(s) specified');
    }

    foreach ($this->modules as $module) {
      $args[] = $module;
    }

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Uninstalling Drupal module(s)');

    return parent::run();
  }

}
