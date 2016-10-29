<?php

namespace Drubo\Config\Environment;

use Drubo\Config\ConfigInterface;

/**
 * Interface for drubo environment configuration classes.
 */
interface EnvironmentConfigInterface extends ConfigInterface {

  /**
   * Set environment name.
   *
   * @param string|null $environmentName
   *   An environment name or NULL.
   *
   * @return static
   */
  public function setEnvironmentName($environmentName);

}
