<?php

namespace Drubo\Robo\Task\Drupal\Module;

use Robo\Result;

/**
 * Trait for module task classes.
 */
trait ModuleTaskTrait {

  /**
   * Module list.
   *
   * @var array
   */
  protected $modules;

  /**
   * Set module(s).
   *
   * @param string|array $module
   *   The name(s) of the module(s) to process.
   *
   * @return static
   */
  public function module($module) {
    $this->modules = is_array($module) ? $module : [$module];

    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drubo\Robo\Task\BaseTask::validate()
   */
  protected function validate() {
    /** @var \Robo\Result $result */
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      if (empty($this->modules)) {
        return Result::error($this, 'No module(s) specified');
      }
    }

    return $result;
  }

}
