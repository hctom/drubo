<?php

namespace Drubo\Environment;

/**
 * Interface for drubo configuration classes.
 */
interface EnvironmentInterface {

  /**
   * Environment cache key name in Robo configuration.
   */
  const CONFIG_KEY = '_drubo.environment';

  /**
   * Environment: None.
   */
  const NONE = '_none_';

  /**
   * Return environment identifier.
   *
   * @return string|null
   *   If set, the current environment identifier, otherwise NULL.
   *
   * @throws \Drubo\Exception\UndefinedEnvironmentException
   */
  public function get();

  /**
   * Environment exists?
   *
   * @param string $environment
   *   An environment identifier.
   *
   * @return bool
   *   Whether the environment identifier exists.
   */
  public function exists($environment);

  /**
   * Set environment.
   *
   * @param string|null $environment
   *   The environment identifier or NULL if no environment should be set.
   *
   * @return static
   *
   * @throws \Drubo\Exception\InvalidEnvironmentException
   */
  public function set($environment);

}
