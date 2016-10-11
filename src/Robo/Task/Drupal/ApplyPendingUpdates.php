<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Exception\TaskException;

/**
 * Robo task: Apply pending Drupal update(s).
 */
class ApplyPendingUpdates extends Exec {

  /**
   * Module name.
   *
   * @var string
   */
  protected $module;

  /**
   * Update N function.
   *
   * @var string
   */
  protected $updateN;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->module = 'all';
  }

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'update:execute';

    if (empty($this->module)) {
      throw new TaskException($this, 'No module name specified');
    }

    $args[] = escapeshellarg($this->module);

    if (!empty($this->updateN)) {
      $args[] = escapeshellarg($this->updateN);
    }

    return $args;
  }

  /**
   * Set module.
   *
   * @param string $module
   *   The name of the module to execute update N function of. Use 'all' to
   *   execute all pending updates.
   *
   * @return static
   */
  public function module($module) {
    $this->module = $module;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Applying pending update(s)');

    return parent::run();
  }

  /**
   * Set update N function.
   *
   * @param string $updateN
   *   A specific update N function to be executed.
   *
   * @return static
   */
  public function updateN($updateN) {
    $this->updateN = $updateN;

    return $this;
  }

}
