<?php

namespace Drubo\Robo\Task\Drupal\Module;

/**
 * Trait for module task classes.
 */
trait ModuleTrait {

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

}
