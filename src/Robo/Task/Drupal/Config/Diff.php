<?php

namespace Drubo\Robo\Task\Drupal\Config;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Diff Drupal configuration.
 */
class Diff extends Exec {

  use ConfigTrait;

  /**
   * Whether to see the changes in reverse.
   *
   * @var bool
   */
  protected $reverse;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->configDirectory = $this->environmentConfig()
      ->get('filesystem.directories.config.path');

    $this->reverse = FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    // Use absolute path for configuration directory.
    $configDirectory = $this->getDrubo()
      ->getAbsolutePath($this->configDirectory);

    $args[] = 'config:diff';
    $args[] = $configDirectory;

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    if (!empty($this->reverse)) {
      $options['reverse'] = NULL;
    }

    return $options;
  }

  /**
   * Set whether to see the changes in reverse.
   *
   * @param bool $reverse
   *   Whether to see the changes in reverse.
   *
   * @return static
   */
  public function reverse($reverse) {
    $this->reverse = $reverse;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Diffing Drupal configuration';
  }

}
