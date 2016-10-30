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
   * @option string $from An optional environment name for the 'from'
   *   environment (defaults to no environment / default values)
   * @option string $to An optional environment name for the 'to' environment
   *   (defaults to environment configured in project configuration)
   *
   * @see \Drubo\Robo\Tasks::environmentCompareBuilder()
   */
  public function environmentCompare($key = NULL, $options = ['from' => NULL, 'to' => NULL]) {
    $environmentName = $this->getDrubo()
      ->getEnvironment()
      ->getName();

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
      'label' => $options['to'] ? ($options['to'] !== EnvironmentInterface::NONE ? $options['to'] : 'defaults') : $environmentName,
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

    $collectionBuilder->getCollection()
      // Generate diff.
      ->add($this->taskDiff()
        ->from($from['data'], $from['label'])
        ->to($to['data'], $to['label']), 'base.diff');

    return $collectionBuilder;
  }

  /**
   * Dump environment configuration values.
   *
   * @param string $key An optional configuraton key (leave empty to view full
   *   config)
   * @option string $environment An optional environment name (defaults to the
   *   current environment name)
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
    // Load available environment names.
    $environmentNames = $this->getDrubo()
      ->getEnvironmentList()
      ->toArray();

    return $environmentNames;
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

    $collectionBuilder->getCollection()
      // Initialize project configuration.
      ->add($this->taskInitializeProjectConfig(), 'project.initializeConfig');

    return $collectionBuilder;
  }

  /**
   * Install project.
   *
   * @see \Drubo\Robo\Tasks::projectInstallCollectionBuilder()
   */
  public function projectInstall() {
    return $this->projectInstallCollectionBuilder()
      ->run();
  }

  /**
   * Return collection builder for 'Install project' command.
   *
   * @return \Robo\Collection\CollectionBuilder
   *   The collection builder prepopulated with general tasks.
   */
  protected function projectInstallCollectionBuilder() {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $collectionBuilder->getCollection()
      // Install latest dependencies.
      ->add($this->taskComposerInstall()
        ->optimizeAutoloader(), 'composer.install')

      // Prepare/ensure directories.
      ->add($this->taskPrepareFilesystemDirectories(), 'filesystem.prepareDirectories')

      // Prepare/ensure files.
      ->add($this->taskPrepareFilesystemFiles(), 'filesystem.prepareFiles')

      // Install Drupal site.
      ->add($this->taskInstallDrupalSite(), 'drupal.installSite')

      // Import Drupal configuration.
      ->add($this->taskImportDrupalConfig(), 'drupal.importConfig')

      // Apply entity schema updates.
      ->add($this->taskDrupalUpdateEntities(), 'drupal.update.entities')

      // Display one-time login URL.
      ->add($this->taskDrupalUserLogin(), 'drupal.userLogin');

    return $collectionBuilder;
  }

  /**
   * Reinstall project.
   *
   * @see \Drubo\Robo\Tasks::projectReinstallCollectionBuilder()
   */
  public function projectReinstall() {
    // Show warning.
    $this->yell(implode("\n", [
      'REINSTALL REQUESTED',
      '-------------------',
      '!!! All data will be lost !!!',
      'This action cannot be undone',
    ]), 40, 'red');

    // Ask for confirmation.
    if (!$this->confirm('Continue')) {
      return Result::cancelled('Reinstall cancelled...');
    }

    return $this->projectReinstallCollectionBuilder()
      ->run();
  }

  /**
   * Return collection builder for 'Reinstall project' command.
   *
   * @return \Robo\Collection\CollectionBuilder
   *   The collection builder prepopulated with general tasks.
   */
  protected function projectReinstallCollectionBuilder() {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $collectionBuilder->getCollection()
      // Install latest dependencies.
      ->add($this->taskComposerInstall()
        ->optimizeAutoloader(), 'composer.install')

      // Prepare/ensure directories.
      ->add($this->taskPrepareFilesystemDirectories(), 'filesystem.prepareDirectories')

      // Prepare/ensure files.
      ->add($this->taskPrepareFilesystemFiles(), 'filesystem.prepareFiles')

      // Reinstall Drupal site.
      ->add($this->taskReinstallDrupalSite(), 'drupal.reinstallSite')

      // Import configuration.
      ->add($this->taskImportDrupalConfig(), 'drupal.importConfig')

      // Apply entity schema updates.
      ->add($this->taskDrupalUpdateEntities(), 'drupal.update.entities')

      // Display one-time login URL.
      ->add($this->taskDrupalUserLogin(), 'drupal.userLogin');

    return $collectionBuilder;
  }

  /**
   * Update project.
   *
   * @see \Drubo\Robo\Tasks::projectUpdateCollectionBuilder()
   */
  public function projectUpdate() {
    return $this->projectUpdateCollectionBuilder()
      ->run();
  }

  /**
   * Return collection builder for 'Update project' command.
   *
   * @return \Robo\Collection\CollectionBuilder
   *   The collection builder prepopulated with general tasks.
   */
  protected function projectUpdateCollectionBuilder() {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    // Build composer install task.
    $composerInstallTask = $this->taskComposerInstall()
      ->optimizeAutoloader();

    $collectionBuilder->getCollection()
      // Install latest dependencies.
      ->add($composerInstallTask, 'composer.install')

      // Prepare/ensure directories.
      ->add($this->taskPrepareFilesystemDirectories(), 'filesystem.prepareDirectories')

      // Prepare/ensure files.
      ->add($this->taskPrepareFilesystemFiles(), 'filesystem.prepareFiles')

      // Apply pending updates.
      ->add($this->taskDrupalUpdateExecute(), 'drupal.update.execute')

      // Import configuration.
      ->add($this->taskImportDrupalConfig(), 'drupal.importConfig')

      // Apply entity schema updates.
      ->add($this->taskDrupalUpdateEntities(), 'drupal.update.entities')

      // Display one-time login URL.
      ->add($this->taskDrupalUserLogin(), 'drupal.userLogin');

    return $collectionBuilder;
  }

  /**
   * Upgrade project.
   *
   * @param array $packageNames An optional array of package names.
   *
   * @see \Drubo\Robo\Tasks::projectUpgradeCollectionBuilder()
   */
  public function projectUpgrade(array $packageNames) {
    return $this->projectUpgradeCollectionBuilder($packageNames)
      ->run();
  }

  /**
   * Return collection builder for 'Upgrade project' command.
   *
   * @param array $packageNames
   *   An array of package names.
   *
   * @return \Robo\Collection\CollectionBuilder
   *   The collection builder prepopulated with general tasks.
   */
  protected function projectUpgradeCollectionBuilder(array $packageNames) {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $collectionBuilder->getCollection()
      // Install latest dependencies.
      ->add($this->taskComposerInstall()
        ->optimizeAutoloader(), 'composer.install')

      // Update packages (dry-run).
      ->add($this->taskComposerUpdate()
        ->args($packageNames)
        ->option('dry-run'), 'composer.update.dryRun')

      // Ask for confirmation to continue.
      ->addCode(function() {
        if (!$this->confirm('Continue')) {
          throw new \RuntimeException('Upgrade cancelled...');
        }
      }, 'composer.update.confirm')

      // Update packages.
      ->add($this->taskComposerUpdate()
        ->optimizeAutoloader()
        ->args($packageNames), 'composer.update')

      // Apply pending updates.
      ->add($this->taskDrupalUpdateExecute(), 'drupal.update.execute')

      // Apply entity schema updates.
      ->add($this->taskDrupalUpdateEntities(), 'drupal.update.entities')

      // Export configuration.
      ->add($this->taskExportDrupalConfig(), 'drupal.exportConfig');

    return $collectionBuilder;
  }

}
