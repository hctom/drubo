<?php

namespace Drubo\Robo\Task\Drupal;

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
   * @return DiffConfig
   */
  protected function taskDiffDrupalConfig() {
    return $this->task(DiffConfig::class);
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
   * @return ExportConfig
   */
  protected function taskExportDrupalConfig() {
    return $this->task(ExportConfig::class);
  }

  /**
   * Import configuration to current Drupal site.
   *
   * @return ImportConfig
   */
  protected function taskImportDrupalConfig() {
    return $this->task(ImportConfig::class);
  }

  /**
   * Install a Drupal site.
   *
   * @return SiteInstall
   */
  protected function taskInstallDrupalSite() {
    return $this->task(SiteInstall::class);
  }

  /**
   * Rebuild and clear Drupal site cache(s).
   *
   * @return RebuildCache
   */
  protected function taskRebuildDrupalCache() {
    return $this->task(RebuildCache::class);
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
   * @return ReinstallSite
   */
  protected function taskReinstallDrupalSite() {
    return $this->task(ReinstallSite::class);
  }

}
