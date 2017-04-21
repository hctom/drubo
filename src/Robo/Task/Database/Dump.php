<?php

namespace Drubo\Robo\Task\Database;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Result;

/**
 * Robo task: Dump database structure and contents.
 */
class Dump extends Exec {

  /**
   * Database identifier.
   *
   * @var string
   */
  protected $database;

  /**
   * Dump file path.
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
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'database:dump';
    $args[] = $this->database;

    return $args;
  }

  /**
   * Set database.
   *
   * @param string $database
   *   The identifier of the database whose structure and contents should be
   *   dumped.
   *
   * @return static
   */
  public function database($database) {
    $this->database = $database;

    return $this;
  }

  /**
   * Set dump file path.
   *
   * @param string $file
   *   The path to the dump file to create.
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
  protected function options() {
    $options = parent::options();

    $options['file'] = $this->file;

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Dumping structure and contents of database';
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

      // No dump file path specified?
      if (empty($this->file)) {
        return Result::error($this, 'No dump file path specified');
      }
    }

    return $result;
  }

}
