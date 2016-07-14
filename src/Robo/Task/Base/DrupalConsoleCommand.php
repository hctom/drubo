<?php

namespace Drubo\Robo\Task\Base;

/**
 * Robo task base class for tasks utilizing Drupal Console.
 */
abstract class DrupalConsoleCommand extends EncapsulatedExec {

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->config()
      ->get('drupalconsole.path');
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Force ANSI output.
    $options['ansi'] = NULL;

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->config()
      ->get('docroot.path');
  }

}
