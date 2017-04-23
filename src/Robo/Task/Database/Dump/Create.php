<?php

namespace Drubo\Robo\Task\Database\Dump;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Result;

/**
 * Robo task: Dump database structure and contents.
 */
class Create extends Exec {

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
  public function options() {
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

      // No database dump file path specified?
      elseif (empty($this->file)) {
        return Result::error($this, 'No database dump file path specified');
      }
    }

    return $result;
  }

}
