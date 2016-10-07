<?php

namespace Drubo\Robo\Task\Database;

trait loadTasks {

  /**
   * Drop all tables in a given database.
   *
   * @return DropTables
   */
  protected function taskDropDatabaseTables() {
    return $this->task(DropTables::class);
  }

}
