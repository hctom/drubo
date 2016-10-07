<?php

namespace Drubo\Environment;

use Robo\Robo;

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
    if (!($environment = Robo::config()->get(static::CACHE_KEY))) {
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
    return Robo::service('drubo.environments');
  }

  /**
   * {@inheritdoc}
   */
  public function set($environment) {
    Robo::config()->set(static::CACHE_KEY, $environment ?: static::NONE);

    return $this;
  }

}
