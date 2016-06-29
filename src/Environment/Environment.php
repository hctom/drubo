<?php

namespace Drubo\Environment;

use Drubo\Drubo;

/**
 * Environment service class for drubo.
 */
class Environment implements EnvironmentInterface {

  /**
   * {@inheritdoc}
   */
  public function exists($environment) {
    return $this->listService()->has($environment);
  }

  /**
   * {@inheritdoc}
   */
  public function get() {
    if (!isset($GLOBALS[static::GLOBALS_KEY])) {
      throw new \Exception('No environment has been set');
    }

    return $GLOBALS[static::GLOBALS_KEY] !== static::NONE ? $GLOBALS['drubo.environment'] : NULL;
  }

  /**
   * Return environment list service.
   *
   * @return \Drubo\Environment\EnvironmentListInterface
   *   The environment list service.
   */
  protected function listService() {
    return Drubo::container()->get('drubo.environment.list');
  }

  /**
   * {@inheritdoc}
   */
  public function set($environment) {
    $GLOBALS[static::GLOBALS_KEY] = $environment ?: static::NONE;

    return $this;
  }

}
