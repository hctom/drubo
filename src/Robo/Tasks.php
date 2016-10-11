<?php

namespace Drubo\Robo;

use Drubo\DruboAwareTrait;
use Robo\ResultData;
use Robo\Tasks as RoboTasks;
use Symfony\Component\Yaml\Yaml;

/**
 * Base class for drubo-enabled RoboFile console commands configuration classes.
 */
abstract class Tasks extends RoboTasks {

  use DruboAwareTrait;
  use \Drubo\Robo\Task\Database\loadTasks;
  use \Drubo\Robo\Task\Drupal\loadTasks;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->drubo()
      // Initialize drubo.
      ->initialize()
      // Add environment-unspecific commands.
      ->registerEnvironmentUnspecificCommands([
        'config:dump',
        'environments',
      ]);
  }

  /**
   * Dump configuration values.
   */
  public function configDump() {
    // Load environment (if any).
    $environment = $this->drubo()
      ->environment()
      ->get();

    // Load configuration.
    $config = $this->drubo()
      ->config($environment)
      ->get();

    return ResultData::message(Yaml::dump($config));
  }

  /**
   * List all available environments.
   */
  public function environments() {
    // Load available environment identifiers.
    $environments = $this->drubo()
      ->getEnvironments()
      ->get();

    return ResultData::message(Yaml::dump($environments));
  }

  /**
   * Install Drupal site.
   */
  public function siteInstall() {
    // TODO Implement Tasks::siteInstall().
  }

  /**
   * Update Drupal site.
   */
  public function siteUpdate() {
    // TODO Implement Tasks::siteUpdate().
  }

}
