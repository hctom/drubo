<?php

namespace Drubo\Robo\Task\DrupalConsole;

use Drubo\Robo\Task\Base\EncapsulatedExec;

/**
 * Robo task base class for Drupal Console execution tasks.
 */
abstract class Exec extends EncapsulatedExec {

  /**
   * Use/force or disable ANSI output?
   *
   * @return bool
   *   Whether to use/force ANSI output (--ansi) or not (--no-ansi).
   */
  protected function ansi() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupalconsole.ansi');
  }

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->getDrubo()
      ->getConfig()
      ->get('drupalconsole.path');
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Use/force ANSI output.
    if ($this->ansi()) {
      $options['ansi'] = NULL;
    }
    else {
      $options['no-ansi'] = NULL;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->getDrubo()
      ->getConfig()
      ->get('docroot.path');
  }

}
