<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\Drupal\Cache\Rebuild as CacheRebuild;
use Drubo\Robo\Task\Drupal\Config\Diff as ConfigDiff;
use Drubo\Robo\Task\Drupal\Config\Export as ConfigExport;
use Drubo\Robo\Task\Drupal\Config\Import as ConfigImport;
use Drubo\Robo\Task\Drupal\Migrate\Import as MigrateImport;
use Drubo\Robo\Task\Drupal\Migrate\Rollback as MigrateRollback;
use Drubo\Robo\Task\Drupal\Module\Install as ModuleInstall;
use Drubo\Robo\Task\Drupal\Module\Uninstall as ModuleUninstall;
use Drubo\Robo\Task\Drupal\Node\AccessRebuild as NodeAccessRebuild;
use Drubo\Robo\Task\Drupal\Site\Install as SiteInstall;
use Drubo\Robo\Task\Drupal\Site\Reinstall as SiteReinstall;
use Drubo\Robo\Task\Drupal\Update\Entities as UpdateEntities;
use Drubo\Robo\Task\Drupal\Update\Execute as UpdateExecute;
use Drubo\Robo\Task\Drupal\User\Login as UserLogin;

trait loadTasks {

  /**
   * Rebuild and clear Drupal site cache(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Cache\Rebuild
   */
  protected function taskDrupalCacheRebuild() {
    return $this->task(CacheRebuild::class);
  }

  /**
   * Diff Drupal configuration.
   *
   * @return \Drubo\Robo\Task\Drupal\Config\Diff
   */
  protected function taskDrupalConfigDiff() {
    return $this->task(ConfigDiff::class);
  }

  /**
   * Export configuration to 'sync' directory.
   *
   * @return \Drubo\Robo\Task\Drupal\Config\Export
   */
  protected function taskDrupalConfigExport() {
    return $this->task(ConfigExport::class);
  }

  /**
   * Import configuration to current Drupal site.
   *
   * @return \Drubo\Robo\Task\Drupal\Config\Import
   */
  protected function taskDrupalConfigImport() {
    return $this->task(ConfigImport::class);
  }

  /**
   * Import Drupal migration(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Migrate\Import
   */
  protected function taskDrupalMigrateImport() {
    return $this->task(MigrateImport::class);
  }

  /**
   * Roll back Drupal migration(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Migrate\Rollback
   */
  protected function taskDrupalMigrateRollback() {
    return $this->task(MigrateRollback::class);
  }

  /**
   * Install Drupal module(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Module\Install
   */
  protected function taskDrupalModuleInstall() {
    return $this->task(ModuleInstall::class);
  }

  /**
   * Uninstall Drupal module(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Module\Uninstall
   */
  protected function taskDrupalModuleUninstall() {
    return $this->task(ModuleUninstall::class);
  }

  /**
   * Rebuild Drupal node access permissions.
   *
   * @return \Drubo\Robo\Task\Drupal\Node\AccessRebuild
   */
  protected function taskDrupalNodeAccessRebuild() {
    return $this->task(NodeAccessRebuild::class);
  }

  /**
   * Install a Drupal site.
   *
   * @return \Drubo\Robo\Task\Drupal\Site\Install
   */
  protected function taskDrupalSiteInstall() {
    return $this->task(SiteInstall::class);
  }

  /**
   * Reinstall a Drupal site.
   *
   * @return \Drubo\Robo\Task\Drupal\Site\Reinstall
   */
  protected function taskDrupalSiteReinstall() {
    return $this->task(SiteReinstall::class);
  }

  /**
   * Apply Drupal entity schema updates.
   *
   * @return \Drubo\Robo\Task\Drupal\Update\Entities
   */
  protected function taskDrupalUpdateEntities() {
    return $this->task(UpdateEntities::class);
  }

  /**
   * Apply pending Drupal update(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Update\Execute
   */
  protected function taskDrupalUpdateExecute() {
    return $this->task(UpdateExecute::class);
  }

  /**
   * Generate one-time user login URL.
   *
   * @return \Drubo\Robo\Task\Drupal\User\Login
   */
  protected function taskDrupalUserLogin() {
    return $this->task(UserLogin::class);
  }

}
