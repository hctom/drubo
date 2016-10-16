<?php

namespace Drubo\Config;

use Drubo\DruboAwareTrait;

/**
 * Trait for methods returning configuration values for site installations.
 */
trait InstallConfigTrait {

  use DruboAwareTrait;

  /**
   * Return administrator account e-mail address.
   *
   * @return string|null
   *   The administrator account e-mail address (if configured).
   */
  protected function accountMail() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupal.account.mail');
  }

  /**
   * Return administrator account name.
   *
   * @return string|null
   *   The administrator account name (if configured).
   */
  protected $accountName;
  protected function accountName() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupal.account.name');
  }

  /**
   * Return administrator account password.
   *
   * @return string|null
   *   The administrator account password (if configured).
   */
  protected function accountPassword() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupal.account.pass');
  }

  /**
   * Return language code.
   *
   * @return string|null
   *   The language code (if configured).
   */
  protected function siteLanguage() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupal.site.language');
  }

  /**
   * Return site e-mail address.
   *
   * @return string|null
   *   The site e-mail address (if configured).
   */
  protected function siteMail() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupal.site.mail');
  }

  /**
   * Return site name.
   *
   * @return string|null
   *   The site name (if configured).
   */
  protected function siteName() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupal.site.name');
  }

  /**
   * Return installation profile name.
   *
   * @return string|null
   *   The installation profile name (if configured)
   */
  protected function siteProfile() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupal.site.profile');
  }

}
