<?php

namespace Drubo\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Interface for drubo configuration classes.
 */
interface ConfigInterface {

  /**
   * Return configuration value.
   *
   * @param string|null $key
   *   An optional configuraton key. Pass NULL to return whole config.
   *
   * @return mixed
   *   The configuration value on success.
   *
   * @throws \InvalidArgumentException
   */
  public function get($key = NULL);

  /**
   * Return configuration schema.
   *
   * @return \Symfony\Component\Config\Definition\ConfigurationInterface
   *   The configuration schema object.
   */
  public function getSchema();

  /**
   * Has configuration value?
   *
   * @param string $key
   *   A configuraton key.
   *
   * @return bool
   *   Whether the configuration key exists.
   */
  public function has($key);

  /**
   * Load configuration.
   *
   * @return static
   */
  public function load();

  /**
   * Set configuration schema.
   *
   * @param \Symfony\Component\Config\Definition\ConfigurationInterface $schema
   *   The configuration schema object.
   *
   * @return static
   */
  public function setSchema(ConfigurationInterface $schema);

}
