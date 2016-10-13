<?php

namespace Drubo\Robo;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Robo\ResultData;
use Robo\Tasks as RoboTasks;
use Symfony\Component\Yaml\Yaml;

/**
 * Base class for drubo-enabled RoboFile console commands configuration classes.
 */
abstract class Tasks extends RoboTasks implements DruboAwareInterface {

  use DruboAwareTrait;
  use \Drubo\Robo\Task\Database\loadTasks;
  use \Drubo\Robo\Task\Drupal\loadTasks;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->getDrubo()
      // Initialize drubo.
      ->initialize()
      // Register commands that do not need an environment context to be set.
      ->registerEnvironmentUnawareCommands([
        'config:dump',
        'environments',
      ]);
  }

  /**
   * Dump configuration values.
   */
  public function configDump() {
    // Load configuration.
    $config = $this->getDrubo()
      ->getConfig()
      ->get();

    return ResultData::message(Yaml::dump($config, PHP_INT_MAX, 2));
  }

  /**
   * List all available environments.
   */
  public function environments() {
    // Load available environment identifiers.
    $environments = $this->getDrubo()
      ->getEnvironmentList()
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
