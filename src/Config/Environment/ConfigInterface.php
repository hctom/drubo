<?php

namespace Drubo\Config\Environment;

use Drubo\Config\ConfigInterface as BaseConfigInterface;

/**
 * Interface for drubo environment configuration classes.
 */
interface ConfigInterface extends BaseConfigInterface {

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
