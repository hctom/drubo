<?php

namespace Drubo\Robo;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Drubo\Environment\EnvironmentInterface;
use Robo\Result;
use Robo\Tasks as RoboTasks;
use Symfony\Component\Yaml\Yaml;

/**
 * Base class for drubo-enabled RoboFile console command configuration classes.
 */
abstract class Tasks extends RoboTasks implements DruboAwareInterface {

  use DruboAwareTrait;
  use \Drubo\Robo\Task\Base\loadTasks;
  use \Drubo\Robo\Task\Database\loadTasks;
  use \Drubo\Robo\Task\Application\loadTasks;
  use \Drubo\Robo\Task\Drupal\loadTasks;
  use \Drubo\Robo\Task\Filesystem\loadTasks;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->getDrubo()
      // Set up drubo.
      ->setup();
  }

  /**
   * Dump application config values.
   *
   * @option string $format The output format
   */
  public function applicationConfig($options = ['format' => 'yaml']) {
    return $this->getDrubo()
      ->getApplicationConfig()
      ->get();
  }

  /**
   * Compare environment configuration values.
   *
   * @param string $environmentTo An optional environment identifier for the
   *   'to' environment (defaults to environment configured in application
   *   configuration)
   * @param string $environmentFrom An optional environment identifier for the
   *   'from' environment (defaults to no environment, to get default values)
   */
  public function environmentCompare($environmentTo = NULL, $environmentFrom = NULL) {
    $from = $this->getDrubo()
      ->getEnvironmentConfig($environmentFrom ?: EnvironmentInterface::NONE)
      ->get();

    $to = $this->getDrubo()
      ->getEnvironmentConfig($environmentTo)
      ->get();

    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    // Buld diff task.
    $diffTask = $this->taskDiff()
      ->from(Yaml::dump($from, PHP_INT_MAX, 2))
      ->to(Yaml::dump($to, PHP_INT_MAX, 2));

    $collectionBuilder
      // Generate diff.
      ->addTask($diffTask);

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
   * Initialize project.
   *
   * @application-config-unaware
   */
  public function projectInit() {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $collectionBuilder
      // Initialize Drubo.
      ->addTask($this->taskInitializDrubo());

    return $collectionBuilder->run();
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
