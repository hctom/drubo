<?php

namespace Drubo;

use Drubo\Config\Config as DruboConfig;
use Drubo\Config\ConfigSchema;
use Drubo\Environment\Environment;
use Drubo\Environment\EnvironmentList;
use Drubo\EventSubscriber\DisabledConsoleCommandSubscriber;
use Drubo\EventSubscriber\EnvironmentSpecificConsoleCommandSubscriber;
use Drubo\EventSubscriber\EnvironmentSubscriber;
use Drubo\EventSubscriber\SaveEnvironmentIdentifierSubscriber;
use League\Container\ContainerInterface;
use Robo\Application;
use Robo\Robo;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Helper class for drubo.
 */
class Drubo {

  /**
   * Package directory cache key name in Robo configuration.
   */
  const CACHE_KEY_PACKAGE_DIRECTORY = '_drubo.packageDirectory';

  /**
   * Working directory cache key name in Robo configuration.
   */
  const CACHE_KEY_WORKING_DIRECTORY = '_drubo.workingDirectory';

  /**
   * Names of commands that do not require an environment identifier.
   *
   * @var array
   */
  protected $environmentUnspecificCommands = [];

  /**
   * Singleton instance.
   *
   * @var \Drubo\Drubo
   */
  protected static $instance;

  /**
   * Whether drubo has been initialized already.
   *
   * @var bool
   */
  protected $initialized;

  /**
   * Constructor.
   */
  public function __construct() {
    // Cache package directory.
    Robo::config()->set(static::CACHE_KEY_PACKAGE_DIRECTORY, realpath(__DIR__ . '/../'));

    // Cache working directory.
    Robo::config()->set(static::CACHE_KEY_WORKING_DIRECTORY, getcwd());
  }

  /**
   * Command requires environment to be set?
   *
   * @param string $commandName
   *   A command name.
   *
   * @return bool
   *   Whether the command requires an environmen to be set.
   */
  public function commandRequiresEnvironment($commandName) {
    return !array_key_exists($commandName, $this->environmentUnspecificCommands);
  }

  /**
   * Return path converted to absolute path (if necessary).
   *
   * @param $path
   *   The path to process.
   *
   * @return string
   *   The absolute path.
   */
  public function getAbsolutePath($path) {
    $fs = new Filesystem();

    if (!$fs->isAbsolutePath($path)) {
      $path = $this->getWorkingDirectory() . DIRECTORY_SEPARATOR . $path;
    }

    return $path;
  }

  /**
   * Return configuration service.
   *
   * @param string|null $environment
   *   An optional environment indicator. Leave empty to ignore environment-specific
   *   configuration overrides.
   *
   * @return \Drubo\Config\ConfigInterface
   *   The configuration service object.
   */
  public function getConfig($environment = NULL) {
    $container = $this->getContainer();

    /** @var \Drubo\Config\ConfigInterface $config */
    $config = $container->get('drubo.config');

    // Initialize configuration object.
    $config
      ->setSchema($container->get('drubo.config.schema'))
      ->load($environment);

    return $config;
  }

  /**
   * Return dependency-injection container.
   *
   * @return \League\Container\ContainerInterface|null
   *   The dependency-injection container.
   *
   * @throws \Drupal\Core\DependencyInjection\ContainerNotInitializedException
   */
  public function getContainer() {
    return Robo::getContainer();
  }

  /**
   * Return environment service.
   *
   * @return \Drubo\Environment\EnvironmentInterface
   *   The environment service object.
   */
  public function getEnvironment() {
    return $this->getContainer()->get('drubo.environment');
  }

  /**
   * Return environment list service.
   *
   * @return \Drubo\Environment\EnvironmentListInterface
   *   The environment list service object.
   */
  public function getEnvironmentList() {
    return $this->getContainer()->get('drubo.environment.list');
  }

  /**
   * Return drubo package directory.
   *
   * @return string
   *   The absolute path to the drubo package directory.
   */
  public function getPackageDirectory() {
    return Robo::config()->get(static::CACHE_KEY_PACKAGE_DIRECTORY);
  }

  /**
   * Return singleton instance.
   *
   * @return \Drubo\Drubo
   */
  public static function getSingleton() {
    if (static::$instance === NULL) {
      static::$instance = new static();
    }

    return static::$instance;
  }

  /**
   * Return current working directory.
   *
   * @return string
   *   The absolute path to the current working directory.
   */
  public function getWorkingDirectory() {
    return Robo::config()->get(static::CACHE_KEY_WORKING_DIRECTORY);
  }

  /**
   * Initialize drubo.
   *
   * @return static
   *
   * @throws \LogicException
   */
  public function initialize() {
    // Has already been initialized?
    if ($this->initialized) {
      throw new \LogicException('drubo has already been initialized');
    }

    /** @var \Robo\Application $application */
    $application = $this->getContainer()->get('application');

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
    $eventDispatcher = $this->getContainer()->get('eventDispatcher');

    $this
      // Add additinal input option.
      ->registerInputOptions($application)
      // Register default services.
      ->registerDefaultServices($this->getContainer())
      // Register event subscribers.
      ->registerEventSubscribers($eventDispatcher)
      // Add default environment-unspecific commands.
      ->registerEnvironmentUnspecificCommands([
        'help',
        'list',
      ]);

    // Set 'initialized' flag.
    $this->initialized = TRUE;

    return $this;
  }

  /**
   * Register default services.
   *
   * @param \League\Container\ContainerInterface $container
   *   The dependency-injection container object.
   *
   * @return static
   */
  protected function registerDefaultServices(ContainerInterface $container) {
    // Add environment list service to container.
    $container->add('drubo.environment.list', new EnvironmentList());

    // Add environment service to container.
    $container->add('drubo.environment', new Environment());

    // Add configuration service to container.
    $container->add('drubo.config', new DruboConfig());

    // Add configuration schema service to container.
    $container->add('drubo.config.schema', new ConfigSchema());

    return $this;
  }

  /**
   * Register command that does not explicitly need an environment identifier.
   *
   * @param string $commandName
   *   The name of the command to add.
   *
   * @return static
   */
  public function registerEnvironmentUnspecificCommand($commandName) {
    return $this->registerEnvironmentUnspecificCommands([$commandName]);
  }

  /**
   * Register commands that do not explicitly need an environment identifier.
   *
   * @param array $commandNames
   *   An array of command names to add.
   *
   * @return static
   */
  public function registerEnvironmentUnspecificCommands(array $commandNames) {
    $tmp = array_merge(array_values($this->environmentUnspecificCommands), array_values($commandNames));

    // Filter duplicates.
    $tmp = array_unique($tmp);

    // Make array associative.
    $this->environmentUnspecificCommands = array_combine($tmp, $tmp);

    return $this;
  }

  /**
   * Register event subscribers.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   *   The event dispatcher object.
   *
   * @return static
   */
  protected function registerEventSubscribers(EventDispatcherInterface $eventDispatcher) {
    // Add event subscriber to save environment identifier.
    $eventDispatcher->addSubscriber(new SaveEnvironmentIdentifierSubscriber());

    // Add event subscriber for environment-specific console commands.
    $eventDispatcher->addSubscriber(new EnvironmentSpecificConsoleCommandSubscriber());

    // Add event subscriber for disabled console commands.
    $eventDispatcher->addSubscriber(new DisabledConsoleCommandSubscriber());

    return $this;
  }

  /**
   * Add global command input options.
   *
   * @param \Robo\Application $application
   *   The Robo application object.
   *
   * @return static
   */
  protected function registerInputOptions(Application $application) {
    // Add global environment option.
    $application->getDefinition()->addOption(new InputOption('env', 'e', InputOption::VALUE_OPTIONAL, 'The environment to operate in.', NULL));

    return $this;
  }

}
