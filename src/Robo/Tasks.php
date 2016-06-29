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

    // Handle execution environment.
    Drubo::handleEnvironment($this->environmentUnspecificCommands());
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
   */
  public function configDump() {
    // Load configuration.
    $config = $this->config(Drubo::environment()->get())->get();

    return ResultData::message(Yaml::dump($config));
  }

  /**
   * List all available environments.
   */
  public function environments() {
    return ResultData::message(Yaml::dump(Drubo::environmentList()->environments()));
  }

  /**
   * Return environment-unspecific commands.
   *
   * All commands listed here do not require an environment identifier but may
   * use it as an optional option.
   *
   * @return array
   *   An array of command names.
   */
  protected function environmentUnspecificCommands() {
    return [
      'config:dump',
      'environments',
      'help',
      'list',
    ];
  }

  /**
   * Install Drupal site.
   */
  public function siteInstall() {
    // TODO Implement Tasks::siteInstall().
  }

  /**
   * Update Drupal site.
   *
   * @param string $environment
   *   An environment identifier.
   */
  public function siteUpdate() {
    // TODO Implement Tasks::siteUpdate().
  }

}
