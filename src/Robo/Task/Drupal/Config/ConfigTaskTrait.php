<?php

namespace Drubo\Robo\Task\Drupal\Config;

use Robo\Result;

/**
 * Trait for config task classes.
 */
trait ConfigTaskTrait {

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

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      if (empty($this->configDirectory)) {
        return Result::error($this, 'No configuration directory specified');
      }
    }

    return $result;
  }

}
