<?php

namespace Drubo\Config\Application;

use Drubo\Config\ConfigInterface;

/**
 * Interface for drubo application configuration classes.
 */
interface ApplicationConfigInterface extends ConfigInterface {

  /**
   * Application configuration exists?
   *
   * @return bool
   *   Whether the application configuration exists.
   */
  public function exists();

}
