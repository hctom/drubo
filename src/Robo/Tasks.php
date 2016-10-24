<?php

namespace Drubo\Robo;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Robo\Result;
use Robo\Tasks as RoboTasks;

/**
 * Base class for drubo-enabled RoboFile console command configuration classes.
 */
abstract class Tasks extends RoboTasks implements DruboAwareInterface {

  use DruboAwareTrait;
  use \Drubo\Robo\Task\Database\loadTasks;
  use \Drubo\Robo\Task\Drubo\loadTasks;
  use \Drubo\Robo\Task\Drupal\loadTasks;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->getDrubo()
      // Initialize drubo.
      ->initialize();
  }

  /**
   * Initialize Drubo application.
   *
   * @application-config-unaware
   */
  public function druboInit() {
    /** @var \Robo\Collection\CollectionBuilder $collection */
    $collectionBuilder = $this->collectionBuilder();

    $collectionBuilder
      // Initialize Drubo.
      ->addTask($this->taskInitializDrubo());

    return $collectionBuilder->run();
  }

  /**
   * Dump environment configuration values.
   *
   * @param string $environment An optional environment identifier
   * @option string $format The output format
   */
  public function environmentConfig($environment = NULL, $options = ['format' => 'yaml']) {
    // Load configuration.
    $config = $this->getDrubo()
      ->getEnvironmentConfig($environment)
      ->get();

    return $config;
  }

  /**
   * List all available environments.
   *
   * @option string $format The output format
   *
   * @application-config-unaware
   */
  public function environmentList($options = ['format' => 'list']) {
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
