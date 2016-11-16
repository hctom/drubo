<?php

namespace Drubo\Robo\Task\Drupal\Migrate;

use Drubo\Robo\Task\Drush\Exec;

/**
 * Robo task: Import Drupal migration(s).
 */
class Import extends Exec {

  use MigrateTaskTrait;

  /**
   * Whether to execute all dependent migrations first. (defaults to FALSE).
   *
   * @var bool
   */
  protected $executeDependencies;

  /**
   * Whether to update previously-imported items with the current data (defaults
   * to FALSE).
   *
   * @var bool
   */
  protected $update;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'migrate-import';

    if (!empty($this->migrations)) {
      $args[] = implode(',', $this->migrations);
    }

    return $args;
  }

  /**
   * Set whether to execute all dependent migrations first.
   *
   * @param bool $executeDependencies
   *   Whether to execute all dependent migrations first.
   *
   * @return static
   */
  public function executeDependencies($executeDependencies) {
    $this->executeDependencies = (bool) $executeDependencies;

    return $this;
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

    if ($this->update) {
      $options['update'] = NULL;
    }

    if ($this->executeDependencies) {
      $options['execute-dependencies'] = NULL;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Importing Drupal migration(s)';
  }

  /**
   * Set whether to update previously-imported items with the current data.
   *
   * @param bool $update
   *   Whether to update previously-imported items with the current data.
   *
   * @return static
   */
  public function update($update) {
    $this->update = (bool) $update;

    return $this;
  }

}
