<?php

namespace Drubo\Robo\Task\Drupal;

use Drubo\Config\InstallConfigTrait;
use Drubo\Robo\Task\DrupalConsole\ExecChain;

/**
 * Robo task: Reinstall a Drupal site.
 */
class ReinstallSite extends ExecChain {

  use InstallConfigTrait;

  /**
   * {@inheritdoc}
   */
  protected function chainFile($packageDirectory) {
    return $packageDirectory . '/.drupalconsole/chain/site/reinstall.yml';
  }

  /**
   * {@inheritdoc}
   */
  protected function chainFilePlaceholderValues() {
    return [
      'account_mail' => $this->accountMail(),
      'account_name' => $this->accountName(),
      'account_pass' => $this->accountPassword(),
      'database' => 'default',
      'langcode' => $this->siteLanguage(),
      'profile' => $this->siteProfile(),
      'site_mail' => $this->siteMail(),
      'site_name' => $this->siteName(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Reinstalling Drupal site');

    return parent::run();
  }

}
