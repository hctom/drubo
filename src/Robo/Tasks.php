<?php

namespace Drubo\Robo;

use Drubo\Config\Config;
use Drubo\Config\ConfigSchema;
use Robo\Config as RoboConfig;
use Robo\Tasks as RoboTasks;

/**
 * Base class for drubo-enabled RoboFile console commands configuration classes.
 */
abstract class Tasks extends RoboTasks {

  /**
   * Constructor.
   */
  public function __construct() {
    $container = RoboConfig::getContainer();

    // Add configuration service to container.
    $container->add('drubo.config.class', new Config());

    // Add configuration schema service to container.
    $container->add('drubo.config.schema', new ConfigSchema());
  }

  /**
   * Return configuration.
   *
   * @return \Drubo\Config\Config
   *   The configuration object.
   */
  protected function config() {
    $container = RoboConfig::getContainer();

    if (!$container->has('drubo.config')) {
      /** @var \Drubo\Config\ConfigInterface $config */
      $config = $container->get('drubo.config.class');

      // Initialize configuration object.
      $config
        ->setSchema($container->get('drubo.config.schema'))
        ->setWorkingDirectory(getcwd())
        ->load();

      // Save initialized configuration object to container.
      $container->add('drubo.config', $config);
    }

    return $container->get('drubo.config');
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
