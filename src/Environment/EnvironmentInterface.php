<?php

namespace Drubo\Environment;

/**
 * Interface for drubo configuration classes.
 */
interface EnvironmentInterface {

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

}
