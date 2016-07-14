<?php

namespace Drubo\Robo\Task\Database;

use Robo\Container\SimpleServiceProvider;

trait loadTasks {

  /**
   * Return services.
   */
  public static function getDatabaseServices() {
    return new SimpleServiceProvider([
      'taskDropDatabaseTables' => DropTables::class,
    ]);
  }

  /**
   * Drop all tables in a given database.
   *
   * @return DropTables
   */
  protected function taskDropDatabaseTables() {
    return $this->task(__FUNCTION__);
  }

}
