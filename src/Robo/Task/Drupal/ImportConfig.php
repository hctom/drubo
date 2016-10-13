<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\DrupalConsole\ExecChain;
use Robo\Exception\TaskException;

/**
 * Robo task: Import configuration to current Drupal site.
 */
class ImportConfig extends ExecChain {

  /**
   * Configuration directory.
   *
   * @var string
   */
  protected $configDirectory;

  /**
   * Constructor.
   */
  public function __construct() {
    $config = $this->getDrubo()
      ->getConfig();

    $this->configDirectory = $config->get('filesystem.directories.config.path');
  }

  /**
   * {@inheritdoc}
   */
  protected function chainFile($packageDirectory) {
    return $packageDirectory . '/.drupalconsole/chain/config/import.yml';
  }

  /**
   * {@inheritdoc}
   */
  protected function chainFilePlaceholderValues() {
    if (empty($this->configDirectory)) {
      throw new TaskException($this, 'No configuration directory specified');
    }

    // Use absolute path for configuration directory.
    $configDirectory = $this->getDrubo()
      ->getAbsolutePath($this->configDirectory);

    return [
      'directory' => $configDirectory,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Importing Drupal configuration');

    return parent::run();
  }

}
