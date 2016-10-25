<?php

namespace Drubo;

use Drubo\Config\Application\ApplicationConfig;
use Drubo\Config\Application\ApplicationConfigSchema;
use Drubo\Config\Environment\EnvironmentConfig;
use Drubo\Config\Environment\EnvironmentConfigSchema;
use Drubo\Environment\Environment;
use Drubo\Environment\EnvironmentInterface;
use Drubo\Environment\EnvironmentList;
use Drubo\EventSubscriber\CommandSubscriber;
use Drubo\EventSubscriber\ConfigSubscriber;
use League\Container\ContainerInterface;
use Robo\Robo;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Helper class for drubo.
 */
class Drubo {

  /**
   * Singleton instance.
   *
   * @var \Drubo\Drubo
   */
  protected static $instance;

  /**
   * Whether drubo has been set up already.
   *
   * @var bool
   */
  protected $setup;

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
   * Return application configuration service.
   *
   * @return \Drubo\Config\Application\ApplicationConfigInterface
   *   The application configuration service object.
   */
  public function getApplicationConfig() {
    $container = $this->getContainer();

    /** @var \Drubo\Config\Application\ApplicationConfigInterface $config */
    $config = $container->get('drubo.application.config');

    // Initialize configuration object.
    $config
      ->setSchema($container->get('drubo.application.config.schema'))
      ->load();

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
   * Return environment configuration service.
   *
   * @param string|null $environment
   *   An optional environment identifier (defaults to current environment from
   *   application configuration).
   * @return \Drubo\Config\Environment\EnvironmentConfigInterface
   *   The configuration service object with data for the current environment
   *   (if not called with specific environment identifier).
   */
  public function getEnvironmentConfig($environment = NULL) {
    $container = $this->getContainer();

    // Load current environment (if not specified).
    if (empty($environment)) {
      $environment = $this->getEnvironment()
        ->get();
    }

    /** @var \Drubo\Config\Environment\EnvironmentConfigInterface $config */
    $config = $container->get('drubo.environment.config');

    // Initialize configuration object.
    $config->setSchema($container->get('drubo.environment.config.schema'));

    // Set environment (if not 'none').
    $config->setEnvironment($environment == EnvironmentInterface::NONE ? NULL : $environment);

    // Load configuration.
    $config->load();

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
   * Return the input object.
   *
   * @return \Symfony\Component\Console\Input\InputInterface
   *   The input object.
   */
  public function getInput() {
    return Robo::input();
  }

  /**
   * Return the output object.
   *
   * @return \Symfony\Component\Console\Output\OutputInterface
   *   The output object.
   */
  public function getOutput() {
    return Robo::output();
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
    return getcwd();
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
    // Register application configuration service.
    $container->add('drubo.application.config', new ApplicationConfig());

    // Register application configuration schema service.
    $container->add('drubo.application.config.schema', new ApplicationConfigSchema());

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
   * Set up drubo.
   *
   * @return static
   *
   * @throws \LogicException
   */
  public function setup() {
    // Has already been set up?
    if ($this->setup) {
      throw new \LogicException('drubo has already been set up');
    }

    $this
      // Register default services.
      ->registerDefaultServices($this->getContainer())
      // Register event subscriber for configuration.
      ->registerEventSubscriber(new ConfigSubscriber())
      // Register event subscriber for console commands.
      ->registerEventSubscriber(new CommandSubscriber());

    // Set 'setup' flag.
    $this->setup = TRUE;

    return $this;
  }

}
