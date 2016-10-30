<?php

namespace Drubo\Robo\Task\Drupal\Config;

use Drubo\Robo\Task\DrupalConsole\ExecChain;
use Robo\Exception\TaskException;
use Robo\Result;

/**
 * Robo task: Import configuration to current Drupal site.
 */
class Import extends ExecChain {

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
   * Configuration has no changes to import?
   *
   * @param \Robo\Result $result
   *   The result object to check.
   *
   * @return bool
   *   Whether the configuration has no changes to import.
   */
  protected function hasNoChanges(Result $result) {
    return preg_match('/' . preg_quote('There are no changes.') . '/i', $result->getOutputData()) ? TRUE : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Importing Drupal configuration');

    /** @var \Robo\Result $result */
    $result = parent::run();

    if (!$result->wasSuccessful() || $this->hasNoChanges($result)) {
      return $result;
    }

    // Generate diff.
    ob_start();
    $result = $this->collectionBuilder()
      ->taskDrupalConfigDiff()
      ->run();
    ob_end_clean();

    // All config has been imported?
    if (!$result->wasSuccessful() || $this->hasNoChanges($result)) {
      return $result;
    }

    return $this->collectionBuilder()
      // Rebuild Drupal caches.
      ->taskDrupalCacheRebuild()
      // Import Drupal configuration again.
      ->taskDrupalConfigImport()
      ->run();
  }

}
