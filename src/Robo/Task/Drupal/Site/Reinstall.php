<?php

namespace Drubo\Robo\Task\Drupal\Site;

use Drubo\Robo\Task\DrupalConsole\ExecChain;

/**
 * Robo task: Reinstall a Drupal site.
 */
class Reinstall extends ExecChain {

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
    $config = $this->environmentConfig();

    return [
      'account_mail' => $config->get('drupal.account.mail'),
      'account_name' => $config->get('drupal.account.name'),
      'account_pass' => $config->get('drupal.account.pass'),
      'database' => 'default',
      'langcode' => $config->get('drupal.site.language'),
      'profile' => $config->get('drupal.site.profile'),
      'site_mail' => $config->get('drupal.site.mail'),
      'site_name' => $config->get('drupal.site.name'),
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
