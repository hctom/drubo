<?php

namespace Drubo\Robo;

use Drubo\Drubo;
use Robo\ResultData;
use Robo\Tasks as RoboTasks;
use Symfony\Component\Yaml\Yaml;

/**
 * Base class for drubo-enabled RoboFile console commands configuration classes.
 */
abstract class Tasks extends RoboTasks {

  /**
   * Constructor.
   */
  public function __construct() {
    // Register services.
    Drubo::registerServices();
  }

  /**
   * Return configuration.
   *
   * @return \Drubo\Config\Config
   *   The configuration object.
   */
  protected function config($environment = NULL) {
    return Drubo::config($environment);
  }

  /**
   * Dump configuration values.
   *
   * @param string|null $environment
   *   An optional environment indicator. Leave empty to ignore environment-specific
   *   configuration overrides.
   */
  public function configDump($environment = NULL) {
    // Validate environment.
    $this->validateEnvironment($environment);

    // Load configuration.
    $config = $this->config($environment)->get();

    return ResultData::message(Yaml::dump($config));
  }

  /**
   * Install Drupal site.
   *
   * @param string $environment
   *   An environment identifier.
   */
  public function siteInstall($environment) {
    // Validate environment.
    $this->validateEnvironment($environment);

    // TODO Implement Tasks::siteInstall().
  }

  /**
   * Update Drupal site.
   *
   * @param string $environment
   *   An environment identifier.
   */
  public function siteUpdate($environment) {
    // Validate environment.
    $this->validateEnvironment($environment);

    // TODO Implement Tasks::siteUpdate().
  }

  /**
   * Validate environment identifier.
   *
   * @param string|null $environment
   *   An environment identifier.
   *
   * @return bool
   *   Whether the environment identifier is valid.
   *
   * @throws \RuntimeException
   */
  protected function validateEnvironment($environment) {
    if (!empty($environment) && !Drubo::environment()->exists($environment)) {
      throw new \RuntimeException('Unknown environment: ' . $environment);
    }

    return TRUE;
  }

}
