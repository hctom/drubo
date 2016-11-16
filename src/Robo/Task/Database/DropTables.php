<?php

namespace Drubo\Robo\Task\Database;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Result;

/**
 * Robo task: Drop all database tables.
 */
class DropTables extends Exec {

  /**
   * Database identifier.
   *
   * @var string
   */
  protected $database;

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

    $args[] = 'database:drop';
    $args[] = $this->database;

    return $args;
  }

  /**
   * Set database.
   *
   * @param string $database
   *   The identifier of the database whose tables should be dropped.
   *
   * @return static
   */
  public function database($database) {
    $this->database = $database;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Dropping all database tables';
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      if (empty($this->database)) {
        return Result::error($this, 'No database specified');
      }
    }

    return $result;
  }

}
