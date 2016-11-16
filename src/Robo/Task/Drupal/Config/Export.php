<?php

namespace Drubo\Robo\Task\Drupal\Config;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Export configuration to 'sync' directory.
 */
class Export extends Exec {

  use ConfigTaskTrait;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->configDirectory = $this->environmentConfig()
      ->get('filesystem.directories.config.path');
  }

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'config:export';

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Use absolute path for configuration directory.
    $configDirectory = $this->getDrubo()
      ->getAbsolutePath($this->configDirectory);

    $options['directory=' . $this->escape($configDirectory)] = NULL;

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Exporting Drupal configuration';
  }

}
