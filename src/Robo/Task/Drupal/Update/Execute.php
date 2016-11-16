<?php

namespace Drubo\Robo\Task\Drupal\Update;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Result;

/**
 * Robo task: Apply pending Drupal update(s).
 */
class Execute extends Exec {

  /**
   * Module name (defaults to 'all').
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
    parent::__construct();

    $this->module = 'all';
  }

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'update:execute';
    $args[] = $this->module;

    if (!empty($this->updateN)) {
      $args[] = $this->updateN;
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
  protected function title() {
    return 'Applying pending update(s)';
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      if (empty($this->module)) {
        return Result::error($this, 'No module name specified');
      }
    }

    return $result;
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
