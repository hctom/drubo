<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\DrupalConsole\ExecChain;
use Robo\Exception\TaskException;
use Robo\Result;

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
    parent::__construct();

    $this->configDirectory = $this->environmentConfig()
      ->get('filesystem.directories.config.path');
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
   * Set configuration directory.
   *
   * @param string $configDirectory
   *   The path to the directory containing all configuration files to import.
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
  public function run() {
    $this->printTaskInfo('Importing Drupal configuration');

    /** @var Result $result */
    $result = parent::run();

    if (!$result->wasSuccessful()) {
      return $result;
    }

    // Generate diff.
    ob_start();
    $result = $this->collectionBuilder()
      ->taskDiffDrupalConfig()
      ->run();
    ob_end_clean();

    if (!$result->wasSuccessful()) {
      return $result;
    }

    // All config has been imported?
    if (!preg_match('/' . preg_quote('There are no changes.') . '/i', $result->getOutputData())) {
      return $this->collectionBuilder()
        // Rebuild Drupal caches.
        ->taskRebuildDrupalCache()
        // Import Drupal configuration again.
        ->taskImportDrupalConfig()
        ->run();
    }

    return $result;
  }

}
