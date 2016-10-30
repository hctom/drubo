<?php

namespace Drubo\Robo\Task\Drupal\Config;

/**
 * Trait for config task classes.
 */
trait ConfigTrait {

  /**
   * Configuration directory.
   *
   * @var string
   */
  protected $configDirectory;

  /**
   * Set configuration directory.
   *
   * @param string $configDirectory
   *   The path to the directory containing exported configuration files.
   *
   * @return static
   */
  public function configDirectory($configDirectory) {
    $this->configDirectory = $configDirectory;

    return $this;
  }

}
