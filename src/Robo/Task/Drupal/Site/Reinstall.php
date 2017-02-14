<?php

namespace Drubo\Robo\Task\Drupal\Site;

use Drubo\Robo\Task\DrupalConsole\ExecChain;

/**
 * Robo task: Reinstall a Drupal site.
 */
class Reinstall extends Install {

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Force installation.
    $options['force'] = NULL;

    // No interaction.
    $options['no-interaction'] = NULL;

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Reinstalling Drupal site';
  }

}
