<?php

namespace Drubo\Robo\Task\Drupal\Migrate;

use Drubo\Robo\Task\Drush\Exec;

/**
 * Robo task: Roll back Drupal migration(s).
 */
class Rollback extends Exec {

  use MigrateTaskTrait;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'migrate-rollback';

    if (!empty($this->migrations)) {
      $args[] = implode(',', $this->migrations);
    }

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    if ($this->all) {
      $options['all'] = NULL;
    }

    if (!empty($this->groups)) {
      $options['group=' . $this->escape(implode(',', $this->groups))] = NULL;
    }

    if (!empty($this->tag)) {
      $options['tag=' . $this->escape($this->tag)] = NULL;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Rolling back Drupal migration(s)';
  }

}
