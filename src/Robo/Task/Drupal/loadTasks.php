<?php

namespace Drubo\Robo\Task\Drupal;

use Robo\Container\SimpleServiceProvider;

trait loadTasks {
  
  /**
   * Return services.
   */
  public static function getDrupalServices() {
    return new SimpleServiceProvider([
      'taskApplyDrupalEntityUpdates' => ApplyEntityUpdates::class,
      'taskApplyPendingDrupalUpdates' => ApplyPendingUpdates::class,
      'taskDrupalUserLogin' => UserLogin::class,
      'taskRebuildDrupalCache' => RebuildCache::class,
      'taskRebuildDrupalNodeAccessPermissions' => RebuildNodeAccessPermissions::class,
      'taskInstallDrupalSite' => InstallSite::class,
      'taskImportDrupalConfig' => ImportConfig::class,
    ]);
  }

  /**
   * Apply Drupal entity schema updates.
   *
   * @return ApplyEntityUpdates
   */
  protected function taskApplyDrupalEntityUpdates() {
    return $this->task(__FUNCTION__);
  }

  /**
   * Apply pending Drupal update(s).
   *
   * @return ApplyPendingUpdates
   */
  protected function taskApplyPendingDrupalUpdates() {
    return $this->task(__FUNCTION__);
  }

  /**
   * Generate one-time user login URL.
   *
   * @return UserLogin
   */
  protected function taskDrupalUserLogin() {
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
   * Rebuild Drupal node access permissions.
   *
   * @return RebuildNodeAccessPermissions
   */
  protected function taskRebuildDrupalNodeAccessPermissions() {
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
