<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Exception\TaskException;

/**
 * Robo task: Diff Drupal configuration.
 */
class DiffConfig extends Exec {

  /**
   * Configuration directory.
   *
   * @var string
   */
  protected $configDirectory;

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

    $args[] = 'config:diff';

    if (empty($this->configDirectory)) {
      throw new TaskException($this, 'No configuration directory specified');
    }

    // Use absolute path for configuration directory.
    $configDirectory = $this->getDrubo()
      ->getAbsolutePath($this->configDirectory);

    $args[] = escapeshellarg($configDirectory);

    return $args;
  }

  /**
   * Set configuration directory.
   *
   * @param string $configDirectory
   *   The path to the directory containing configuration to diff.
   *
   * @return static
   */
  public function configDirectory($configDirectory) {
    $this->configDirectory = $configDirectory;

    return $this;
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
  public function run() {
    $this->printTaskInfo('Diffing Drupal configuration');

    return parent::run();
  }

}
