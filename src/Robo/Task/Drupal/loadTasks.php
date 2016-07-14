<?php

namespace Drubo\Robo\Task\Drupal;

use Robo\Container\SimpleServiceProvider;

trait loadTasks {
  
  /**
   * Return services.
   */
  public static function getDrupalServices() {
    return new SimpleServiceProvider([
      'taskRebuildDrupalCache' => RebuildCache::class,
      'taskImportDrupalConfig' => ImportConfig::class,
    ]);
  }

  /**
   * Rebuild and clear Drupal site cache(s).
   *
   * @return RebuildCache
   */
  protected function taskRebuildDrupalCache() {
    return $this->task(__FUNCTION__);
  }

  /**
   * Import configuration to current Drupal site.
   *
   * @return ImportConfig
   */
  protected function taskImportDrupalConfig() {
    return $this->task(__FUNCTION__);
  }

}