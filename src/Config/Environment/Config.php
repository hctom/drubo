<?php

namespace Drubo\Config\Environment;

use Drubo\Config\Config as BaseConfig;
use Drubo\Config\ConfigLoader;
use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * Configuration for drubo.
 */
class Config extends BaseConfig implements ConfigInterface, DruboAwareInterface {

  use DruboAwareTrait;

  /**
   * Configuration file name.
   *
   * @var string
   */
  const FILENAME = 'config.yml';

  /**
   * Environment identifier.
   *
   * @var string
   */
  protected $environment;

  /**
   * Return configuration directory candidates.
   *
   * @return array
   *   An array of directory paths to locate drubo configuration files in.
   */
  protected function getConfigDirectoryCandidates() {
    $workingDirectory = $this->getDrubo()
      ->getWorkingDirectory();

    $paths = [];

    // Path candidate for custom global configuration.
    $paths[] = rtrim($workingDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.drubo';

    // Path candidate for custom environment-specific configuration.
    if (!empty($this->environment)) {
      $paths[] = $paths[0] . DIRECTORY_SEPARATOR . $this->environment;
    }

    return $paths;
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    $configs = [];
    $locator = new FileLocator($this->getConfigDirectoryCandidates());
    $loader = new ConfigLoader($locator);

    $packageDirectory = $this->getDrubo()
      ->getPackageDirectory();

    // Load shipped default configuration.
    $configs[] = $loader->load(rtrim($packageDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'config.default.yml');

    // Locate and load custom configration files (if any).
    if (($files = $locator->locate(static::FILENAME, NULL, FALSE))) {
      foreach ($files as $file) {
        $configs[] = $loader->load($file);
      }
    }

    // Process configuration (if any).
    if (!empty($configs)) {
      $this->data = (new Processor())->processConfiguration($this->getSchema(), $configs);
    }
    else {
      $this->data = [];
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setEnvironment($environment) {
    $this->environment = $environment;

    return $this;
  }

}
