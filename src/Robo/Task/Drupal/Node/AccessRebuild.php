<?php

namespace Drubo\Robo\Task\Drupal\Node;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Rebuild Drupal node access permissions.
 */
class AccessRebuild extends Exec {

  /**
   * Process in batch mode.
   *
   * @var bool
   */
  protected $batch;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->batch = FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'node:access:rebuild';

    return $args;
  }

  /**
   * Set batch mode.
   *
   * @param bool $batch
   *   Whether to process nodes in batch mode.
   *
   * @return static
   */
  public function batch($batch) {
    $this->batch = (bool) $batch;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    if ($this->batch) {
      $options['batch'] = NULL;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Rebuilding node access permissions';
  }

}
