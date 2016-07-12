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
    return $this->environments()->has($environment);
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
   * Return environments service.
   *
   * @return \Drubo\Environment\EnvironmentsInterface
   *   The environments service.
   */
  protected function environments() {
    return Config::service('drubo.environments');
  }

  /**
   * {@inheritdoc}
   */
  public function set($environment) {
    Config::set(static::CACHE_KEY, $environment ?: static::NONE);

    return $this;
  }

}
