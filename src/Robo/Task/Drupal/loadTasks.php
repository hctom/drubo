<?php

namespace Drubo\Robo\Task\Drupal;

use Robo\Container\SimpleServiceProvider;

trait loadTasks {
  
  /**
   * Return services.
   */
  public static function getDrupalServices() {
    return new SimpleServiceProvider([
      'taskApplyEntityUpdates' => ApplyEntityUpdates::class,
      'taskRebuildDrupalCache' => RebuildCache::class,
      'taskInstallDrupalSite' => InstallSite::class,
      'taskImportDrupalConfig' => ImportConfig::class,
    ]);
  }

  /**
   * Apply Drupal entity schema updates.
   *
   * @return ApplyEntityUpdates
   */
  protected function taskApplyEntityUpdates() {
    return $this->task(__FUNCTION__);
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

  /**
   * Install a Drupal site.
   *
   * @return InstallSite
   */
  protected function taskInstallDrupalSite() {
    return $this->task(__FUNCTION__);
  }

}
