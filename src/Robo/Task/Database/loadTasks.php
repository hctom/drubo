<?php

namespace Drubo\Robo\Task\Database;

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
   * @return Dump
   */
  protected function taskDatabaseDump() {
    return $this->task(Dump::class);
  }

}
