<?php

namespace Drubo\Environment;

use Drubo\Exception\InvalidEnvironmentException;
use Drubo\Exception\UndefinedEnvironmentException;
use Robo\Robo;

/**
 * Environment service class for drubo.
 */
class Environment implements EnvironmentInterface {

  /**
   * {@inheritdoc}
   */
  public function exists($environment) {
    return $this->getEnvironmentList()
      ->has($environment);
  }

  /**
   * {@inheritdoc}
   */
  public function get() {
    $environment = Robo::config()
      ->get(static::CACHE_KEY);

    if (!$environment) {
      throw new UndefinedEnvironmentException('Environment is not defined');
    }

    return $environment !== static::NONE ? $environment : NULL;
  }

  /**
   * Return environment list service.
   *
   * @return \Drubo\Environment\EnvironmentListInterface
   *   The environment list service.
   */
  protected function getEnvironmentList() {
    return Robo::service('drubo.environment.list');
  }

  /**
   * {@inheritdoc}
   */
  public function set($environment) {
    Robo::config()->set(static::CACHE_KEY, $environment ?: static::NONE);

    // Environment identifier is valid?
    if (!empty($environment) && !$this->exists($environment)) {
      throw new InvalidEnvironmentException($environment);
    }

    return $this;
  }

}
