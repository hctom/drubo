<?php

namespace Drubo;

use Drubo\Config\Config as DruboConfig;
use Drubo\Config\ConfigSchema;
use Drubo\Environment\Environment;
use Drubo\Environment\Environments;
use Robo\Config as RoboConfig;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Helper class for drubo.
 */
class Drubo {

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
    // Cache working directory.
    RoboConfig::set(static::CACHE_KEY_WORKING_DIRECTORY, getcwd());
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
  public function absolutePath($path) {
    $fs = new Filesystem();

    if (!$fs->isAbsolutePath($path)) {
      $path = $this->workingDirectory() . DIRECTORY_SEPARATOR . $path;
    }

    return $path;
  }

  /**
   * Add name of command that does not explicitly need an environment identifier.
   *
   * @param string $commandName
   *   The name of the command to add.
   *
   * @return static
   */
  public function addEnvironmentUnspecificCommand($commandName) {
    return $this->addEnvironmentUnspecificCommands([$commandName]);
  }

  /**
   * Add names of commands that do not explicitly need an environment identifier.
   *
   * @param array $commandName
   *   An array of command names to add.
   *
   * @return static
   */
  public function addEnvironmentUnspecificCommands(array $commandNames) {
    $tmp = array_merge(array_values($this->environmentUnspecificCommands), array_values($commandNames));

    // Filter duplicates.
    $tmp = array_unique($tmp);

    // Make array associative.
    $this->environmentUnspecificCommands = array_combine($tmp, $tmp);

    return $this;
  }

  /**
   * Add global command input options.
   *
   * @return static
   */
  protected function addInputOptions() {
    /** @var \Robo\Application $application */
    $application = $this->container()->get('application');

    // Add global environment option.
    $application->getDefinition()->addOption(new InputOption('env', 'e', InputOption::VALUE_OPTIONAL, 'The environment to operate in.', NULL));

    return $this;
  }

  /**
   * Add event listeners.
   *
   * @return static
   */
  protected function addListeners() {
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher */
    $dispatcher = $this->container()->get('eventDispatcher');

    // Add event listener for environment handling.
    $dispatcher->addListener(ConsoleEvents::COMMAND, array($this, 'onHandleEnvironment'));

    return $this;
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
    $container = $this->container();

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
  public function container() {
    return RoboConfig::getContainer();
  }

  /**
   * Return environment service.
   *
   * @return \Drubo\Environment\EnvironmentInterface
   */
  public function environment() {
    return $this->container()->get('drubo.environment');
  }

  /**
   * Return environments service.
   *
   * @return \Drubo\Environment\EnvironmentsInterface
   */
  public function environments() {
    return $this->container()->get('drubo.environments');
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
      // Add event listeners.
      ->addListeners()
      // Register default services.
      ->registerDefaultServices()
      // Add default environment-unspecific commands
      ->addEnvironmentUnspecificCommands([
        'help',
        'list',
      ]);

    // Set 'initialized' flag.
    $this->initialized = TRUE;

    return $this;
  }

  /**
   * Event listener: Environment handling.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   */
  public function onHandleEnvironment(ConsoleCommandEvent $event) {
    $environment = $event->getInput()->getOption('env');

    // Environment is required, but not set?
    if (empty($environment) && !array_key_exists($event->getCommand()->getName(), $this->environmentUnspecificCommands)) {
      throw new \RuntimeException('Environment is missing');
    }

    // Environment identifier is set and exists?
    elseif (!empty($environment) && !$this->environment()->exists($environment)) {
      throw new \RuntimeException('Unknown environment: ' . $environment);
    }

    // Save environment identifier for later usage.
    $this->environment()->set($environment);
  }

  /**
   * Register default services.
   *
   * @return static
   */
  protected function registerDefaultServices() {
    $container = $this->container();

    // Add environments service to container.
    $container->add('drubo.environments', new Environments());

    // Add environment service to container.
    $container->add('drubo.environment', new Environment());

    // Add configuration service to container.
    $container->add('drubo.config', new DruboConfig());

    // Add configuration schema service to container.
    $container->add('drubo.config.schema', new ConfigSchema());

    // Add task services to container.

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
    return RoboConfig::get(static::CACHE_KEY_WORKING_DIRECTORY);
  }

}