<?php

namespace Drubo\Robo;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Robo\Tasks as RoboTasks;

/**
 * Base class for drubo-enabled RoboFile console command configuration classes.
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
   *
   * @option $format The output format to use.
   */
  public function configDump($options = ['format' => 'yaml']) {
    // Load configuration.
    $config = $this->getDrubo()
      ->getConfig()
      ->get();

    return $config;
  }

  /**
   * List all available environments.
   *
   * @option $format The output format to use.
   */
  public function environments($options = ['format' => 'list']) {
    // Load available environment identifiers.
    $environments = $this->getDrubo()
      ->getEnvironmentList()
      ->toArray();

    return $environments;
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
