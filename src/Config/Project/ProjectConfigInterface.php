<?php

namespace Drubo\Config\Project;

use Drubo\Config\ConfigInterface;

/**
 * Interface for drubo project configuration classes.
 */
interface ProjectConfigInterface extends ConfigInterface {

  /**
   * Project configuration exists?
   *
   * @return bool
   *   Whether the project configuration exists.
   */
  public function exists();

}
