<?php

namespace Drubo\Config\Environment;

use Drubo\Config\ConfigInterface;

/**
 * Interface for drubo environment configuration classes.
 */
interface EnvironmentConfigInterface extends ConfigInterface {

  /**
   * Set environment.
   *
   * @param string|null $environment
   *   An environment identifier or NULL.
   *
   * @return static
   */
  public function setEnvironment($environment);

}
