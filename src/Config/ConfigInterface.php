<?php

namespace Drubo\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Interface for drubo configuration classes.
 */
interface ConfigInterface {

  /**
   * Configuration file name.
   *
   * @var string
   */
  const FILENAME = 'config.yml';

  /**
   * Return configuration value.
   *
   * @param string|null $key
   *   An optional configuraton key. Pass NULL to return whole config.
   *
   * @return mixed
   *   The configuration value on success.
   */
  public function get($key);

  /**
   * Return configuration schema.
   *
   * @return \Symfony\Component\Config\Definition\ConfigurationInterface
   *   The configuration schema object.
   */
  public function getSchema();

  /**
   * Return working directory.
   *
   * @return string
   *   The working directory path.
   */
  public function getWorkingDirectory();

  /**
   * Load configuration.
   *
   * @param string|null $environment
   *   An optional environment indicator. Leave empty to ignore environment-specific
   *   configuration overrides.
   *
   * @return static
   */
  public function load($environment = NULL);

  /**
   * Set configuration schema.
   *
   * @param \Symfony\Component\Config\Definition\ConfigurationInterface $schema
   *   The configuration schema object.
   *
   * @return static
   */
  public function setSchema(ConfigurationInterface $schema);

  /**
   * Set working directory.
   *
   * @param $workingDirectory
   *   The working directory path.
   *
   * @return static
   */
  public function setWorkingDirectory($workingDirectory);

}
