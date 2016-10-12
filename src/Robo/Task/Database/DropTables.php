<?php

namespace Drubo\Robo\Task\Database;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Exception\TaskException;

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
    $this->database = 'default';
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Robo\Exception\TaskException
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'database:drop';

    if (empty($this->database)) {
      throw new TaskException($this, 'No database specified');
    }

    $args[] = escapeshellarg($this->database);

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
  public function run() {
    $this->printTaskInfo('Dropping all database tables');

    return parent::run();
  }

}
