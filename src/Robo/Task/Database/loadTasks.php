<?php

namespace Drubo\Robo\Task\Database;

use Drubo\Robo\Task\Database\Dump\Create as DumpCreate;
use Drubo\Robo\Task\Database\Dump\Restore as DumpRestore;

trait loadTasks {

  /**
   * Drop all tables in a given database.
   *
   * @return DropTables
   */
  protected function taskDatabaseDropTables() {
    return $this->task(DropTables::class);
  }

  /**
   * Dump structure and contents of a given database.
   *
   * @return \Drubo\Robo\Task\Database\Dump\Create
   */
  protected function taskDatabaseDumpCreate() {
    return $this->task(DumpCreate::class);
  }

  /**
   * Restore structure and contents of a given database from dump file.
   *
   * @return \Drubo\Robo\Task\Database\Dump\Restore
   */
  protected function taskDatabaseDumpRestore() {
    return $this->task(DumpRestore::class);
  }

}
