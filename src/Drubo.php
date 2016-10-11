<?php

namespace Drubo;

use Drubo\Config\Config as DruboConfig;
use Drubo\Config\ConfigSchema;
use Drubo\Environment\Environment;
use Drubo\Environment\Environments;
use Drubo\EventSubscriber\ConsoleCommandSubscriber;
use Drubo\EventSubscriber\EnvironmentSubscriber;
use Robo\Robo;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Input\InputOption;
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
   * Add global command input options.
   *
   * @return static
   */
  protected function addInputOptions() {
    /** @var \Robo\Application $application */
    $application = $this->getContainer()->get('application');

    // Add global environment option.
    $application->getDefinition()->addOption(new InputOption('env', 'e', InputOption::VALUE_OPTIONAL, 'The environment to operate in.', NULL));

    return $this;
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
   * Return configuration service.
   *
   * @param string|null $environment
   *   An optional environment indicator. Leave empty to ignore environment-specific
   *   configuration overrides.
   *
   * @return \Drubo\Config\ConfigInterface
   *   The configuration service.
   */
  public function config($environment = NULL) {
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
   * Return environment service.
   *
   * @return \Drubo\Environment\EnvironmentInterface
   */
  public function environment() {
    return $this->getContainer()->get('drubo.environment');
  }

  /**
   * Return environments service.
   *
   * @return \Drubo\Environment\EnvironmentsInterface
   */
  public function environments() {
    return $this->getContainer()->get('drubo.environments');
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
      $path = $this->workingDirectory() . DIRECTORY_SEPARATOR . $path;
    }

    return $path;
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
   * Return drubo package directory.
   *
   * @return string
   *   The absolute path to the drubo package directory.
   */
  public function getPackageDirectory() {
    return Robo::config()->get(static::CACHE_KEY_PACKAGE_DIRECTORY);
  }

  /**
   * Initialize drubo.
   *
   * @return static
   */
  public function initialize() {
    // Has already been initialized?
    if ($this->initialized) {
      throw new \RuntimeException('drubo has already been initialized');
    }

    $this
      // Add additinal input option.
      ->addInputOptions()
      // Register default services.
      ->registerDefaultServices()
      // Register event subscribers.
      ->registerEventSubscribers()
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
   * @return static
   */
  protected function registerDefaultServices() {
    $container = $this->getContainer();

    // Add environments service to container.
    $container->add('drubo.environments', new Environments());

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
   * @return static
   */
  protected function registerEventSubscribers() {
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher */
    $dispatcher = $this->getContainer()->get('eventDispatcher');

    // Initialize event subscriber objects.
    $consoleCommandSubscriber = new ConsoleCommandSubscriber();
    $environmentSubscriber = new EnvironmentSubscriber();

    // Add event subscriber for environment handling.
    $dispatcher->addListener(ConsoleEvents::COMMAND, array($environmentSubscriber, 'onSaveIdentifier'));

    // Add event subscriber to check if console command requires an environment.
    $dispatcher->addListener(ConsoleEvents::COMMAND, array($consoleCommandSubscriber, 'onCheckEnvironmentIsRequired'));

    // Add event subscriber to check console command disabled state.
    $dispatcher->addListener(ConsoleEvents::COMMAND, array($consoleCommandSubscriber, 'onCheckDisabledState'));

    return $this;
  }

  /**
   * Return singleton instance.
   *
   * @return \Drubo\Drubo
   */
  public static function singleton() {
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
  public function workingDirectory() {
    return Robo::config()->get(static::CACHE_KEY_WORKING_DIRECTORY);
  }

}
