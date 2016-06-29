<?php

namespace Drubo;

use Drubo\Config\Config;
use Drubo\Config\ConfigSchema;
use Drubo\Environment\Environment;
use Drubo\Environment\EnvironmentList;
use Robo\Config as RoboConfig;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;

/**
 * Helper class for drubo.
 */
class Drubo {

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
  public static function config($environment = NULL) {
    $container = static::container();

    /** @var \Drubo\Config\ConfigInterface $config */
    $config = $container->get('drubo.config.class');

    // Initialize configuration object.
    $config
      ->setSchema($container->get('drubo.config.schema'))
      ->setWorkingDirectory(getcwd())
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
  public static function container() {
    return RoboConfig::getContainer();
  }

  /**
   * Return environment service.
   * 
   * @return \Drubo\Environment\EnvironmentInterface
   */
  public static function environment() {
    return static::container()->get('drubo.environment');
  }

  /**
   * Return environment list service.
   *
   * @return \Drubo\Environment\EnvironmentListInterface
   */
  public static function environmentList() {
    return static::container()->get('drubo.environment.list');
  }

  /**
   * Handle execution environment.
   *
   * @param array $environmentUnspecificCommands
   *   An array of command names that do not require an environment identifier.
   */
  public static function handleEnvironment(array $environmentUnspecificCommands = []) {
    $container = static::container();

    /** @var \Robo\Application $application */
    $application = $container->get('application');

    // Add global environment command option.
    $application->getDefinition()->addOption(new InputOption('env', 'e', InputOption::VALUE_OPTIONAL, 'The environment to operate in.', NULL));

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher */
    $dispatcher = $container->get('eventDispatcher');

    // Add event listener for environment handling.
    $dispatcher->addListener(ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) use ($environmentUnspecificCommands) {
      $environment = $event->getInput()->getOption('env');

      // Environment is required, but not set?
      if (empty($environment) && !in_array($event->getCommand()->getName(), $environmentUnspecificCommands, TRUE)) {
        throw new \RuntimeException('Environment is missing');
      }

      // Environment identifier is set and exists?
      elseif (!empty($environment) && !Drubo::environment()->exists($environment)) {
        throw new \RuntimeException('Unknown environment: ' . $environment);
      }

      // Save environment identifier for later usage.
      static::environment()->set($environment);
    });
  }

  /**
   * Register default services.
   */
  public static function registerServices() {
    $container = static::container();

    // Add environment list service to container.
    $container->add('drubo.environment.list', new EnvironmentList());

    // Add environment service to container.
    $container->add('drubo.environment', new Environment());

    // Add configuration service to container.
    $container->add('drubo.config.class', new Config());

    // Add configuration schema service to container.
    $container->add('drubo.config.schema', new ConfigSchema());
  }

}
