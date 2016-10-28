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
   */
  public function environmentCompare($key = NULL, $options = ['from' => NULL, 'to' => NULL]) {
    $from = $this->getDrubo()
      ->getEnvironmentConfig($options['from'] ?: EnvironmentInterface::NONE)
      ->get($key);

    $to = $this->getDrubo()
      ->getEnvironmentConfig($options['to'])
      ->get($key);

    $environment = $this->getDrubo()
      ->getEnvironment()
      ->get();

    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    // Build diff task.
    $diffTask = $this->taskDiff()
      ->from(Yaml::dump($from, PHP_INT_MAX, 2))
      ->to(Yaml::dump($to, PHP_INT_MAX, 2));

    $collectionBuilder
      // Display diff information.
      ->addCode(function() use ($environment, $options) {
        $from = $options['from'] && $options['from'] !== EnvironmentInterface::NONE ? $options['from'] : 'defaults';
        $to = $options['to'] ? ($options['to'] !== EnvironmentInterface::NONE ? $options['to'] : 'defaults') : $environment;

        $this->getDrubo()
          ->getOutput()
          ->writeln(sprintf("Changes from <info>%s</info> to <info>%s</info>\n", $from, $to));
      })
      // Generate diff.
      ->addTask($diffTask);

    return $collectionBuilder->run();
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
   * @project-config-unaware
   */
  public function projectInit() {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $collectionBuilder
      // Initialize project configuration.
      ->addTask($this->taskInitializeProjectConfig());

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
