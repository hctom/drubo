<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Config\InstallConfigTrait;
use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Install a Drupal site.
 */
class InstallSite extends Exec {

  use InstallConfigTrait;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'site:install';

    if (($siteProfile = $this->siteProfile())) {
      $args[] = escapeshellarg($siteProfile);
    }

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Administrator account e-mail address.
    if (($accountMail = $this->accountMail())) {
      $options['account-mail=' . escapeshellarg($accountMail)] = NULL;
    }

    // Administrator account name.
    if (($accountName = $this->accountName())) {
      $options['account-name=' . escapeshellarg($accountName)] = NULL;
    }

    // Administrator account password.
    if (($accountPassword = $this->accountPassword())) {
      $options['account-pass=' . escapeshellarg($accountPassword)] = NULL;
    }

    // Site language.
    if (($siteLanguage = $this->siteLanguage())) {
      $options['langcode=' . escapeshellarg($siteLanguage)] = NULL;
    }

    // Site e-mail address.
    if (($siteMail = $this->siteMail())) {
      $options['site-mail=' . escapeshellarg($siteMail)] = NULL;
    }

    // Site name.
    if (($siteName = $this->siteName())) {
      $options['site-name=' . escapeshellarg($siteName)] = NULL;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Installing Drupal site');

    return parent::run();
  }

}
