<?php

namespace Drubo\Config;

use Drubo\Drubo;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * Configuration for drubo.
 */
class Config implements ConfigInterface {

  /**
   * Configuration data.
   *
   * @var array
   */
  protected $data;

  /**
   * Configuration schema.
   *
   * @var \Symfony\Component\Config\Definition\ConfigurationInterface
   */
  protected $schema;

  /**
   * {@inheritdoc}
   */
  public function get($key = NULL) {
    if (empty($key)) {
      return $this->data;
    }

    $tmp = $this->data;
    $keyParts = explode('.', $key);
    foreach ($keyParts as $i => $keyPart) {
      if (!array_key_exists($keyPart, $tmp)) {
        throw new \InvalidArgumentException('Unknown configuration key: ' . $key);
      }

      $tmp = $tmp[$keyPart];

      if ($i === count($keyParts) - 1) {
        return $tmp;
      }
    }
  }

  /**
   * Return configuration directory candidates.
   *
   * @param string|null $environment
   *   An optional environment indicator. Leave empty to ignore environment-specific
   *   configuration overrides.
   *
   * @return array
   *   An array of directory paths to locate drubo configuration files in.
   */
  protected function getConfigDirectoryCandidates($environment = NULL) {
    $workingDirectory = Drubo::getSingleton()
      ->getWorkingDirectory();

    $paths = [];

    // Path candidate for default configuration.
    $paths[] = rtrim($workingDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.drubo';

    // Path candidate for environment-specific configuration.
    if (!empty($environment)) {
      $paths[] = $paths[0] . DIRECTORY_SEPARATOR . $environment;
    }

    return $paths;
  }

  /**
   * {@inheritdoc}
   */
  public function getSchema() {
    return $this->schema;
  }

  /**
   * {@inheritdoc}
   */
  public function has($key) {
    $keyParts = explode('.', $key);
    $tmp = $this->data;

    foreach ($keyParts as $i => $keyPart) {
      if (!array_key_exists($keyPart, $tmp)) {
        return FALSE;
      }

      $tmp = $tmp[$keyPart];

      if ($i === count($keyParts) - 1) {
        return TRUE;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function load($environment = NULL) {
    $configs = [];
    $locator = new FileLocator($this->getConfigDirectoryCandidates($environment));
    $loader = new ConfigLoader($locator);

    // Locate and load configration files (if any).
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
  public function setSchema(ConfigurationInterface $schema) {
    $this->schema = $schema;

    return $this;
  }

}
