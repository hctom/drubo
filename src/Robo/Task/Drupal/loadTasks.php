<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\Drupal\Cache\Rebuild;
use Drubo\Robo\Task\Drupal\Config\Diff;
use Drubo\Robo\Task\Drupal\Config\Export;
use Drubo\Robo\Task\Drupal\Config\Import;
use Drubo\Robo\Task\Drupal\Node\AccessRebuild;
use Drubo\Robo\Task\Drupal\Site\Install;
use Drubo\Robo\Task\Drupal\Site\Reinstall;
use Drubo\Robo\Task\Drupal\Update\Entities;
use Drubo\Robo\Task\Drupal\Update\Execute;
use Drubo\Robo\Task\Drupal\User\Login;

trait loadTasks {

  /**
   * Diff Drupal configuration.
   *
   * @return \Drubo\Robo\Task\Drupal\Config\Diff
   */
  protected function taskDrupalConfigDiff() {
    return $this->task(Diff::class);
  }

  /**
   * Export configuration to 'sync' directory.
   *
   * @return \Drubo\Robo\Task\Drupal\Config\Export
   */
  protected function taskDrupalConfigExport() {
    return $this->task(Export::class);
  }

  /**
   * Apply Drupal entity schema updates.
   *
   * @return \Drubo\Robo\Task\Drupal\Update\Entities
   */
  protected function taskDrupalUpdateEntities() {
    return $this->task(Entities::class);
  }

  /**
   * Apply pending Drupal update(s).
   *
   * @return \Drubo\Robo\Task\Drupal\Update\Execute
   */
  protected function taskDrupalUpdateExecute() {
    return $this->task(Execute::class);
  }

  /**
   * Generate one-time user login URL.
   *
   * @return \Drubo\Robo\Task\Drupal\User\Login
   */
  protected function taskDrupalUserLogin() {
    return $this->task(Login::class);
  }

  /**
   * Import configuration to current Drupal site.
   *
   * @return \Drubo\Robo\Task\Drupal\Config\Import
   */
  protected function taskImportDrupalConfig() {
    return $this->task(Import::class);
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
   * @return \Drubo\Robo\Task\Drupal\Node\AccessRebuild
   */
  protected function taskRebuildDrupalNodeAccessPermissions() {
    return $this->task(AccessRebuild::class);
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
