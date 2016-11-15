<?php

namespace Drubo\Robo\Task\Drupal\Module;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Exception\TaskException;

/**
 * Robo task: Install Drupal module(s).
 */
class Install extends Exec {

  use ModuleTrait;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'module:install';

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
    $this->printTaskInfo('Installing Drupal module(s)');

    return parent::run();
  }

}
