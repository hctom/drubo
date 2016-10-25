<?php

namespace Drubo\Config\Project;

use Drubo\Config\Config;
use Drubo\Config\ConfigLoader;
use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * Project configuration for drubo.
 */
class ProjectConfig extends Config implements ProjectConfigInterface, DruboAwareInterface  {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public function exists() {
    return is_file($this->file());
  }

  /**
   * Return configuration file.
   *
   * @return string
   *   The absolute file path to the project configuration file.
   */
  protected function file() {
    return $this->workingDirectory() . DIRECTORY_SEPARATOR . '.drubo.yml';
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    $workingDirectory = static::workingDirectory();

    $locator = new FileLocator([$workingDirectory]);
    $loader = new ConfigLoader($locator);

    $file = $this->file();

    // Load and process initialization configration files (if exists).
    if (is_file($file)) {
      $config = $loader->load($file);
      $this->data = (new Processor())->processConfiguration($this->getSchema(), [$config]);
    }
    else {
      $this->data = [];
    }

    return $this;
  }

  /**
   * Return current working directory.
   *
   * @return string
   *   The absolute path to the current working directory.
   */
  protected function workingDirectory() {
    return $this->getDrubo()
      ->getWorkingDirectory();
  }

}
