<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\Drupal\Cache\Rebuild;
use Drubo\Robo\Task\Drupal\Config\Diff;
use Drubo\Robo\Task\Drupal\Site\Install;
use Drubo\Robo\Task\Drupal\Site\Reinstall;

trait loadTasks {

  /**
   * Apply Drupal entity schema updates.
   *
   * @return ApplyEntityUpdates
   */
  protected function taskApplyDrupalEntityUpdates() {
    return $this->task(ApplyEntityUpdates::class);
  }

  /**
   * Apply pending Drupal update(s).
   *
   * @return ApplyPendingUpdates
   */
  protected function taskApplyPendingDrupalUpdates() {
    return $this->task(ApplyPendingUpdates::class);
  }

  /**
   * Diff Drupal configuration .
   *
   * @return \Drubo\Robo\Task\Drupal\Config\Diff
   */
  protected function taskDiffDrupalConfig() {
    return $this->task(Diff::class);
  }

  /**
   * Generate one-time user login URL.
   *
   * @return UserLogin
   */
  protected function taskDrupalUserLogin() {
    return $this->task(UserLogin::class);
  }

  /**
   * Export configuration to 'sync' directory.
   *
   * @return ConfigExport
   */
  protected function taskExportDrupalConfig() {
    return $this->task(ConfigExport::class);
  }

  /**
   * Import configuration to current Drupal site.
   *
   * @return ConfigImport
   */
  protected function taskImportDrupalConfig() {
    return $this->task(ConfigImport::class);
  }

  /**
   * Install a Drupal site.
   *
   * @return \Drubo\Robo\Task\Drupal\Site\Install
   */
  protected function taskInstallDrupalSite() {
    return $this->task(Install::class);
  }

  /**
   * Rebuild and clear Drupal site cache(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Cache\Rebuild
   */
  protected function taskRebuildDrupalCache() {
    return $this->task(Rebuild::class);
  }

  /**
   * Rebuild Drupal node access permissions.
   *
   * @return RebuildNodeAccessPermissions
   */
  protected function taskRebuildDrupalNodeAccessPermissions() {
    return $this->task(RebuildNodeAccessPermissions::class);
  }

  /**
   * Reinstall a Drupal site.
   *
   * @return \Drubo\Robo\Task\Drupal\Site\Reinstall
   */
  protected function taskReinstallDrupalSite() {
    return $this->task(Reinstall::class);
  }

}
