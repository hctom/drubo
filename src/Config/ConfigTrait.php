<?php

namespace Drubo\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * Trait for drubo configuration classes.
 */
trait ConfigTrait {

  /**
   * Working directory path.
   *
   * @var string
   */
  protected $workingDirectory;

  /**
   * Configuration data.
   *
   * @var array
   */
  protected $data;

  /**
   * Configuration schema.
   *
   * @var ConfigurationInterface
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
   * @return array
   *   An array of directory paths to locate drubo configuration files in.
   */
  protected function getConfigDirectoryCandidates() {
    $path = '';
    $paths = [];

    foreach (explode(DIRECTORY_SEPARATOR, $this->workingDirectory) as $part) {
      $path .= $part . DIRECTORY_SEPARATOR;
      $paths[] = rtrim($path, DIRECTORY_SEPARATOR);
    }

    return array_reverse($paths);
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
  public function getWorkingDirectory() {
    return $this->workingDirectory;
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    $locator = new FileLocator($this->getConfigDirectoryCandidates());
    $loader = new ConfigLoader($locator);

    // Config validation.
    $processor = new Processor();

    if (($file = $locator->locate(static::FILENAME, NULL, TRUE))) {
      $this->data = $processor->processConfiguration($this->getSchema(), [
        'drubo' => $loader->load($file)
      ]);
    }
    else {
      $this->data = [];
    }

    dump($this->data);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setSchema(ConfigurationInterface $schema) {
    $this->schema = $schema;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setWorkingDirectory($workingDirectory) {
    $this->workingDirectory = $workingDirectory;

    return $this;
  }

}
