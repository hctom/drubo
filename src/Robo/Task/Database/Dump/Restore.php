<?php

namespace Drubo\Robo\Task\Database\Dump;

use Drubo\Robo\Task\DrupalConsole\ExecChain;
use Robo\Result;

/**
 * Robo task: Restore database structure and contents from dump file.
 */
class Restore extends ExecChain {

  /**
   * Database identifier.
   *
   * @var string
   */
  protected $database;

  /**
   * Database dump file path.
   *
   * @var string
   */
  protected $file;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->database = 'default';
  }

  /**
   * {@inheritdoc}
   */
  protected function chainFile($packageDirectory) {
    return $packageDirectory . '/.drupalconsole/chain/database/dump/restore.yml';
  }

  /**
   * {@inheritdoc}
   */
  protected function chainFilePlaceholderValues() {
    return [
      'database' => $this->database,
      'file' => $this->file,
    ];
  }

  /**
   * Set database.
   *
   * @param string $database
   *   The identifier of the database to process.
   *
   * @return static
   */
  public function database($database) {
    $this->database = $database;

    return $this;
  }

  /**
   * Set database dump file path.
   *
   * @param string $file
   *   The path to the database dump file.
   *
   * @return static
   */
  public function file($file) {
    $this->file = $this->getDrubo()
      ->getAbsolutePath($file);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Restoring structure and contents of database';
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      // No database specified?
      if (empty($this->database)) {
        return Result::error($this, 'No database specified');
      }

      // No database dump file path specified?
      elseif (empty($this->file)) {
        return Result::error($this, 'No database dump file path specified');
      }
    }

    return $result;
  }

}
