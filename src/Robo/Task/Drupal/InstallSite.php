<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Install a Drupal site.
 */
class InstallSite extends Exec {

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'site:install';

    $siteProfile = $this->environmentConfig()
      ->get('drupal.site.profile');

    if ($siteProfile) {
      $args[] = escapeshellarg($siteProfile);
    }

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    $config = $this->environmentConfig();

    // Administrator account e-mail address.
    if (($accountMail = $config->get('drupal.account.mail'))) {
      $options['account-mail=' . escapeshellarg($accountMail)] = NULL;
    }

    // Administrator account name.
    if (($accountName = $config->get('drupal.account.name'))) {
      $options['account-name=' . escapeshellarg($accountName)] = NULL;
    }

    // Administrator account password.
    if (($accountPassword = $config->get('drupal.account.pass'))) {
      $options['account-pass=' . escapeshellarg($accountPassword)] = NULL;
    }

    // Site language.
    if (($siteLanguage = $config->get('drupal.site.language'))) {
      $options['langcode=' . escapeshellarg($siteLanguage)] = NULL;
    }

    // Site e-mail address.
    if (($siteMail = $config->get('drupal.site.mail'))) {
      $options['site-mail=' . escapeshellarg($siteMail)] = NULL;
    }

    // Site name.
    if (($siteName = $config->get('drupal.site.name'))) {
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
