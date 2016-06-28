<?php

namespace Drubo;

use Drubo\Config\Config;
use Drubo\Config\ConfigSchema;
use Drubo\Environment\Environment;
use Drubo\Environment\EnvironmentInterface;
use Drubo\Environment\EnvironmentList;
use Robo\Config as RoboConfig;

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
   * @return \Symfony\Component\DependencyInjection\ContainerInterface|null
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
