<?php

namespace Drubo;

use Drubo\Config\Environment\Config as EnvironmentConfig;
use Drubo\Config\Environment\ConfigSchema as EnvironmentConfigSchema;
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
   * Working directory cache key name in Robo configuration.
   */
  const CONFIG_KEY_WORKING_DIRECTORY = '_drubo.workingDirectory';

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
    // Cache working directory.
    Robo::config()->set(static::CONFIG_KEY_WORKING_DIRECTORY, getcwd());
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
   * Return environment configuration service.
   *
   * @return \Drubo\Config\Environment\ConfigInterface
   *   The configuration service object with data for the current environment
   *   (if set, otherwise defaults will be returned).
   */
  public function getEnvironmentConfig() {
    $environment = $this->getEnvironment()
      ->get();

    $container = $this->getContainer();

    /** @var \Drubo\Config\Environment\ConfigInterface $config */
    $config = $container->get('drubo.environment.config');

    // Initialize configuration object.
    $config
      ->setSchema($container->get('drubo.environment.config.schema'))
      ->setEnvironment($environment)
      ->load();

    return $config;
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
    return realpath(__DIR__ . '/../');
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
    return Robo::config()->get(static::CONFIG_KEY_WORKING_DIRECTORY);
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
    $config = $this->getEnvironmentConfig();

    $key = 'drubo.commands.' . $commandName . '.disabled';

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
    // Register environment service.
    $container->add('drubo.environment', new Environment());

    // Register environment configuration service.
    $container->add('drubo.environment.config', new EnvironmentConfig());

    // Register environment configuration schema service.
    $container->add('drubo.environment.config.schema', new EnvironmentConfigSchema());

    // Register environment list service.
    $container->add('drubo.environment.list', new EnvironmentList());

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
