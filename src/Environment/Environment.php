<?php

namespace Drubo\Environment;

use Robo\Config;

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
    if (!($environment = Config::get(static::CACHE_KEY))) {
      throw new \Exception('No environment has been set');
    }

    return $environment !== static::NONE ? $environment : NULL;
  }

  /**
   * Return environment list service.
   *
   * @return \Drubo\Environment\EnvironmentListInterface
   *   The environment list service.
   */
  protected function listService() {
    return Config::service('drubo.environment.list');
  }

  /**
   * {@inheritdoc}
   */
  public function set($environment) {
    Config::set(static::CACHE_KEY, $environment ?: static::NONE);

    return $this;
  }

}
