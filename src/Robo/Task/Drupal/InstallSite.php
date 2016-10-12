<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Robo\Task\DrupalConsole\Exec;

/**
 * Robo task: Install a Drupal site.
 */
class InstallSite extends Exec {

  /**
   * Account e-mail address.
   *
   * @var string|null
   */
  protected $accountMail;

  /**
   * Account name.
   *
   * @var string|null
   */
  protected $accountName;

  /**
   * Account password.
   *
   * @var string|null
   */
  protected $accountPass;

  /**
   * Language code.
   *
   * @var string|null
   */
  protected $languageCode;

  /**
   * Installation profile.
   *
   * @var string|null
   */
  protected $profile;

  /**
   * Site e-mail address.
   *
   * @var string|null
   */
  protected $siteMail;

  /**
   * Site name.
   *
   * @var string|null
   */
  protected $siteName;

  /**
   * Constructor.
   */
  public function __construct() {
    $config = $this->getDrubo()
      ->getConfig();

    $this->accountMail = $config->get('account.mail');
    $this->accountName = $config->get('account.name');
    $this->accountPass = $config->get('account.pass');
    $this->languageCode = $config->get('site.language');
    $this->profile = $config->get('site.profile');
    $this->siteName = $config->get('site.name');
    $this->siteMail = $config->get('site.mail');
  }

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'site:install';

    if ($this->profile) {
      $args[] = escapeshellarg($this->profile);
    }

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    if ($this->accountMail) {
      $options['account-mail=' . escapeshellarg($this->accountMail)] = NULL;
    }

    if ($this->accountName) {
      $options['account-name=' . escapeshellarg($this->accountName)] = NULL;
    }

    if ($this->accountPass) {
      $options['account-pass=' . escapeshellarg($this->accountPass)] = NULL;
    }

    if ($this->languageCode) {
      $options['langcode=' . escapeshellarg($this->languageCode)] = NULL;
    }

    if ($this->siteMail) {
      $options['site-mail=' . escapeshellarg($this->siteMail)] = NULL;
    }

    if ($this->siteName) {
      $options['site-name=' . escapeshellarg($this->siteName)] = NULL;
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
