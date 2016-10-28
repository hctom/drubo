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
  use \Drubo\Robo\Task\Project\loadTasks;
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
   * Compare environment configuration values.
   *
   * @param string $key An optional configuraton key (leave empty to diff full
   *   config)
   * @option string $from An optional environment identifier for the 'from'
   *   environment (defaults to no environment / default values)
   * @option string $to An optional environment identifier for the 'to'
   *   environment (defaults to environment configured in project configuration)
   *
   * @see \Drubo\Robo\Tasks::environmentCompareBuilder()
   */
  public function environmentCompare($key = NULL, $options = ['from' => NULL, 'to' => NULL]) {
    $environment = $this->getDrubo()
      ->getEnvironment()
      ->get();

    $configFrom = $this->getDrubo()
      ->getEnvironmentConfig($options['from'] ?: EnvironmentInterface::NONE)
      ->get($key);

    $configTo = $this->getDrubo()
      ->getEnvironmentConfig($options['to'])
      ->get($key);

    $from = [
      'data' => Yaml::dump($configFrom, PHP_INT_MAX, 2),
      'label' => $options['from'] && $options['from'] !== EnvironmentInterface::NONE ? $options['from'] : 'defaults',
    ];

    $to = [
      'data' => Yaml::dump($configTo, PHP_INT_MAX, 2),
      'label' => $options['to'] ? ($options['to'] !== EnvironmentInterface::NONE ? $options['to'] : 'defaults') : $environment
    ];

    return $this->environmentCompareBuilder($from, $to)
      ->run();
  }

  /**
   * Return collection builder for 'Compare environment configuration values'
   * command.
   *
   * @param array $from
   *   The 'from' information array with the following keys:
   *     - data: The data to compare.
   *     - label: The label to display.
   * @param array $to
   *   The 'to' information array with the following keys:
   *     - data: The data to compare.
   *     - label: The label to display.
   *
   * @return \Robo\Collection\CollectionBuilder
   *   The collection builder prepopulated with general tasks.
   */
  protected function environmentCompareBuilder(array $from, array $to) {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    // Build diff task.
    $diffTask = $this->taskDiff()
      ->from($from['data'], $from['label'])
      ->to($to['data'], $to['label']);

    $collectionBuilder
      ->getCollection()
      // Generate diff.
      ->add($diffTask, 'base.diff');

    return $collectionBuilder;
  }

  /**
   * Dump environment configuration values.
   *
   * @param string $key An optional configuraton key (leave empty to view full
   *   config)
   * @option string $environment An optional environment identifier (defaults to
   *   the current environment)
   * @option string $format The output format
   */
  public function environmentConfig($key = NULL, $options = ['environment|e' => NULL, 'format' => 'yaml']) {
    // Load configuration.
    $config = $this->getDrubo()
      ->getEnvironmentConfig($options['environment'])
      ->get($key);

    return $config;
  }

  /**
   * List all available environments.
   *
   * @option string $format The output format
   *
   * @project-config-unaware
   */
  public function environmentList($options = ['format' => 'list']) {
    // Load available environment identifiers.
    $environments = $this->getDrubo()
      ->getEnvironmentList()
      ->toArray();

    return $environments;
  }

  /**
   * Dump project configuration values.
   *
   * @option string $format The output format
   */
  public function projectConfig($options = ['format' => 'yaml']) {
    // Load configuration.
    $config = $this->getDrubo()
      ->getProjectConfig()
      ->get();

    return $config;
  }

  /**
   * Initialize project.
   *
   * @see \Drubo\Robo\Tasks::projectInitCollectionBuilder()
   *
   * @project-config-unaware
   */
  public function projectInit() {
    return $this->projectInitCollectionBuilder()
      ->run();
  }

  /**
   * Return collection builder for 'Initialize project' command.
   *
   * @return \Robo\Collection\CollectionBuilder
   *   The collection builder prepopulated with general tasks.
   */
  protected function projectInitCollectionBuilder() {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $collectionBuilder
      ->getCollection()
      // Initialize project configuration.
      ->add($this->taskInitializeProjectConfig(), 'project.initializeConfig');

    return $collectionBuilder;
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
