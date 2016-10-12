<?php

namespace Drubo;

use Drubo\Config\Config as DruboConfig;
use Drubo\Config\ConfigSchema;
use Drubo\Environment\Environment;
use Drubo\Environment\EnvironmentList;
use Drubo\EventSubscriber\DisabledCommandSubscriber;
use Drubo\EventSubscriber\EnvironmentAwareCommandSubscriber;
use Drubo\EventSubscriber\SaveEnvironmentIdentifierSubscriber;
use League\Container\ContainerInterface;
use Robo\Robo;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
   * Names of commands that do not require an environment context to be set.
   *
   * @var array
   */
  protected $environmentUnawareCommands = [];

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
   * Return configuration service for current environment.
   *
   * @return \Drubo\Config\ConfigInterface
   *   The configuration service object with data for the current environment
   *   (if set, otherwise defaults will be returned).
   */
  public function getConfig() {
    $environment = $this->getEnvironment()
      ->get();

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

    $this
      // Register global input option for environment.
      ->registerInputOption(new InputOption('env', 'e', InputOption::VALUE_OPTIONAL, 'The environment to operate in.', NULL))
      // Register default services.
      ->registerDefaultServices($this->getContainer())
      // Register event subscriber to save environment identifier.
      ->registerEventSubscriber(new SaveEnvironmentIdentifierSubscriber())
      // Register event subscriber for environment-specific console commands.
      ->registerEventSubscriber(new EnvironmentAwareCommandSubscriber())
      // Register event subscriber for disabled console commands.
      ->registerEventSubscriber(new DisabledCommandSubscriber())
      // Register commands that do not need an environment context to be set.
      ->registerEnvironmentUnawareCommands([
        'help',
        'list',
      ]);

    // Set 'initialized' flag.
    $this->initialized = TRUE;

    return $this;
  }

  /**
   * Command is disabled?
   *
   * @param string $commandName
   *   A command name.
   *
   * @return bool
   *   Whether the command is disabled.
   */
  public function isDisabledCommand($commandName) {
    $config = $this->getConfig();

    $key = 'commands.' . $commandName . '.disabled';

    // Command status configuration exists?
    if ($config->has($key)) {
      return $config->get($key) === TRUE;
    }

    return FALSE;
  }

  /**
   * Command requires environment context to be set?
   *
   * @param string $commandName
   *   A command name.
   *
   * @return bool
   *   Whether the command requires an environmen context to be set.
   */
  public function isEnvironmentAwareCommand($commandName) {
    return !array_key_exists($commandName, $this->environmentUnawareCommands);
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
    // Register environment list service to container.
    $container->add('drubo.environment.list', new EnvironmentList());

    // Register environment service to container.
    $container->add('drubo.environment', new Environment());

    // Register configuration service to container.
    $container->add('drubo.config', new DruboConfig());

    // Register configuration schema service to container.
    $container->add('drubo.config.schema', new ConfigSchema());

    return $this;
  }

  /**
   * Register command that does not explicitly need an environment context to be
   * set.
   *
   * @param string $commandName
   *   The name of the command to add.
   *
   * @return static
   */
  public function registerEnvironmentUnawareCommand($commandName) {
    return $this->registerEnvironmentUnawareCommands([$commandName]);
  }

  /**
   * Register commands that do not explicitly need an environment context to be
   * set.
   *
   * @param array $commandNames
   *   An array of command names to add.
   *
   * @return static
   */
  public function registerEnvironmentUnawareCommands(array $commandNames) {
    $tmp = array_merge(array_values($this->environmentUnawareCommands), array_values($commandNames));

    // Filter duplicates.
    $tmp = array_unique($tmp);

    // Make array associative.
    $this->environmentUnawareCommands = array_combine($tmp, $tmp);

    return $this;
  }

  /**
   * Register event subscriber.
   *
   * @param \Symfony\Component\EventDispatcher\EventSubscriberInterface $eventSubscriber
   *   The event subscriber object.
   *
   * @return static
   */
  public function registerEventSubscriber(EventSubscriberInterface $eventSubscriber) {
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher */
    $eventDispatcher = $this->getContainer()
      ->get('eventDispatcher');

    // Register event subscriber.
    $eventDispatcher->addSubscriber($eventSubscriber);

    return $this;
  }

  /**
   * Register global input option for console commands.
   *
   * @param \Symfony\Component\Console\Input\InputOption $inputOption
   *   The input option object.
   *
   * @return static
   */
  public function registerInputOption(InputOption $inputOption) {
    /** @var \Robo\Application $application */
    $application = $this->getContainer()
      ->get('application');

    // Add global environment option.
    $application->getDefinition()
      ->addOption($inputOption);

    return $this;
  }

}
